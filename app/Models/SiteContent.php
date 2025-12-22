<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'section',
        'label',
        'type',
        'value',
        'default_value',
        'image_path',
        'help_text',
        'order',
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    const CACHE_TTL = 3600;

    /**
     * Get content value by key with caching
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("site_content.{$key}", self::CACHE_TTL, function () use ($key, $default) {
            $content = self::where('key', $key)->first();
            
            if (!$content) {
                return $default;
            }

            // For images, return the image_path or value
            if ($content->type === 'image') {
                return $content->image_path ?? $content->value ?? $content->default_value;
            }

            return $content->value ?? $content->default_value ?? $default;
        });
    }

    /**
     * Get all content for a section
     * 
     * @param string $section
     * @return \Illuminate\Support\Collection
     */
    public static function getSection(string $section)
    {
        return Cache::remember("site_content.section.{$section}", self::CACHE_TTL, function () use ($section) {
            return self::where('section', $section)
                ->orderBy('order')
                ->get()
                ->keyBy('key')
                ->map(function ($item) {
                    if ($item->type === 'image') {
                        return $item->image_path ?? $item->value ?? $item->default_value;
                    }
                    return $item->value ?? $item->default_value;
                });
        });
    }

    /**
     * Update content value and clear cache
     * 
     * @param string $key
     * @param mixed $value
     * @param string|null $imagePath
     * @return bool
     */
    public static function set(string $key, $value, ?string $imagePath = null): bool
    {
        $content = self::where('key', $key)->first();
        
        if (!$content) {
            return false;
        }

        $data = ['value' => $value];
        
        if ($imagePath !== null) {
            $data['image_path'] = $imagePath;
        }

        $result = $content->update($data);

        // Clear caches
        Cache::forget("site_content.{$key}");
        Cache::forget("site_content.section.{$content->section}");

        return $result;
    }

    /**
     * Clear all site content cache
     */
    public static function clearCache(): void
    {
        $sections = self::distinct('section')->pluck('section');
        
        foreach ($sections as $section) {
            Cache::forget("site_content.section.{$section}");
        }

        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("site_content.{$key}");
        }
    }

    /**
     * Get all sections with their contents for admin
     * 
     * @return array
     */
    public static function getAllForAdmin(): array
    {
        $sections = [
            'global' => 'Configuración Global',
            'home' => 'Página de Inicio',
            'about' => 'Página Acerca de',
            'contact' => 'Información de Contacto',
            'banners' => 'Banners Promocionales',
        ];

        $result = [];

        foreach ($sections as $sectionKey => $sectionLabel) {
            $result[$sectionKey] = [
                'label' => $sectionLabel,
                'contents' => self::where('section', $sectionKey)
                    ->orderBy('order')
                    ->get()
            ];
        }

        return $result;
    }

    /**
     * Check if content has custom value (different from default)
     */
    public function hasCustomValue(): bool
    {
        if ($this->type === 'image') {
            return !empty($this->image_path);
        }

        return $this->value !== $this->default_value && !empty($this->value);
    }

    /**
     * Reset to default value
     */
    public function resetToDefault(): bool
    {
        $result = $this->update([
            'value' => $this->default_value,
            'image_path' => null,
        ]);

        Cache::forget("site_content.{$this->key}");
        Cache::forget("site_content.section.{$this->section}");

        return $result;
    }
}
