<?php

if (!function_exists('image_url')) {
    /**
     * Get the correct URL for an image path.
     * Handles both Cloudinary URLs (https://...) and local storage paths.
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */
    function image_url(?string $path, string $default = ''): string
    {
        if (empty($path)) {
            return $default;
        }

        // If it's already a full URL (Cloudinary, etc.), return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Otherwise, it's a local storage path
        return asset('storage/' . ltrim($path, '/'));
    }
}

if (!function_exists('content_image_url')) {
    /**
     * Get the correct URL for a site content image.
     * Checks if it's a Cloudinary URL, a storage path, or a default asset path.
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */
    function content_image_url(?string $path, string $default = ''): string
    {
        if (empty($path)) {
            return $default ? asset($default) : '';
        }

        // If it's already a full URL (Cloudinary, etc.), return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // If it starts with content/, it's in storage
        if (str_starts_with($path, 'content/')) {
            return asset('storage/' . $path);
        }

        // Otherwise, assume it's an asset path (like images/default.png)
        return asset($path);
    }
}

if (!function_exists('get_tax_rate')) {
    /**
     * Get the configured tax rate (IVA) from site content.
     *
     * @param bool $asDecimal If true, returns as decimal (0.12), otherwise as percentage (12)
     * @return float
     */
    function get_tax_rate(bool $asDecimal = false): float
    {
        $rate = (float) \App\Models\SiteContent::get('global.tax_rate', 12);
        return $asDecimal ? ($rate / 100) : $rate;
    }
}
