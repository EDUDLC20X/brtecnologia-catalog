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

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
    }

    /**
     * Check if Cloudinary is configured
     */
    public function isConfigured(): bool
    {
        $configured = !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
        
        if (!$configured) {
            Log::info('Cloudinary config check', [
                'cloud_name' => $this->cloudName ? 'SET' : 'NOT SET',
                'api_key' => $this->apiKey ? 'SET' : 'NOT SET',
                'api_secret' => $this->apiSecret ? 'SET' : 'NOT SET',
            ]);
        }
        
        return $configured;
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
            
            // Parameters for signature (alphabetically sorted)
            $params = [
                'folder' => $folder,
                'timestamp' => $timestamp,
            ];

            // Generate signature - Cloudinary requires specific format
            $signature = $this->generateSignature($params);

            Log::info('Cloudinary upload attempt', [
                'cloud_name' => $this->cloudName,
                'folder' => $folder,
                'timestamp' => $timestamp,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ]);

            // Use base64 encoding for the file
            $fileContent = base64_encode(file_get_contents($file->getPathname()));
            $mimeType = $file->getMimeType();
            $base64File = "data:{$mimeType};base64,{$fileContent}";

            $response = Http::asForm()
                ->timeout(120)
                ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                    'file' => $base64File,
                    'api_key' => $this->apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $folder,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Cloudinary upload successful', [
                    'public_id' => $data['public_id'],
                    'url' => $data['secure_url'],
                ]);
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
                'trace' => $e->getTraceAsString(),
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
     * Cloudinary requires: sha1(params_string + api_secret)
     * where params_string is "key1=value1&key2=value2" (sorted alphabetically, no URL encoding)
     */
    protected function generateSignature(array $params): string
    {
        ksort($params);
        
        // Build string without URL encoding
        $parts = [];
        foreach ($params as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        $stringToSign = implode('&', $parts) . $this->apiSecret;
        
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
