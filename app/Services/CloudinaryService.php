<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;
    protected $uploadPreset;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
        $this->uploadPreset = config('services.cloudinary.upload_preset', 'ml_default');
    }

    /**
     * Check if Cloudinary is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array|null Returns ['url' => '...', 'public_id' => '...'] or null on failure
     */
    public function upload(UploadedFile $file, string $folder = 'products'): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning('Cloudinary not configured, falling back to local storage');
            return null;
        }

        try {
            $timestamp = time();
            $params = [
                'folder' => $folder,
                'timestamp' => $timestamp,
            ];

            // Generate signature
            $signature = $this->generateSignature($params);

            $response = Http::asMultipart()
                ->timeout(60)
                ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                    ['name' => 'file', 'contents' => fopen($file->getPathname(), 'r'), 'filename' => $file->getClientOriginalName()],
                    ['name' => 'api_key', 'contents' => $this->apiKey],
                    ['name' => 'timestamp', 'contents' => $timestamp],
                    ['name' => 'signature', 'contents' => $signature],
                    ['name' => 'folder', 'contents' => $folder],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'url' => $data['secure_url'],
                    'public_id' => $data['public_id'],
                ];
            }

            Log::error('Cloudinary upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function delete(string $publicId): bool
    {
        if (!$this->isConfigured() || empty($publicId)) {
            return false;
        }

        try {
            $timestamp = time();
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];

            $signature = $this->generateSignature($params);

            $response = Http::asForm()
                ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                    'public_id' => $publicId,
                    'api_key' => $this->apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Cloudinary delete exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Generate signature for Cloudinary API
     */
    protected function generateSignature(array $params): string
    {
        ksort($params);
        $stringToSign = http_build_query($params) . $this->apiSecret;
        return sha1($stringToSign);
    }

    /**
     * Get optimized URL with transformations
     */
    public function getOptimizedUrl(string $url, int $width = 800, int $height = 800): string
    {
        // If it's already a Cloudinary URL, add transformations
        if (str_contains($url, 'cloudinary.com')) {
            // Insert transformation before /upload/
            return preg_replace(
                '/(\/upload\/)/',
                "/upload/w_{$width},h_{$height},c_limit,q_auto,f_auto/",
                $url
            );
        }

        return $url;
    }
}
