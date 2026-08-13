<?php

if (!function_exists('cdn_asset')) {
    /**
     * Generate a URL for an asset, preferring the R2 CDN if configured,
     * otherwise falling back to the local asset() helper.
     *
     * @param  string  $path
     * @return string
     */
    function cdn_asset($path)
    {
        // If it's already an absolute URL, just return it
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $r2Url = rtrim(config('filesystems.disks.r2.url'), '/');
        
        // If R2 URL is configured in .env, serve from CDN
        if (!empty($r2Url)) {
            // Trim leading slash from path to prevent double slashes
            $cleanPath = ltrim($path, '/');
            return $r2Url . '/' . $cleanPath;
        }

        // Fallback to local asset
        return asset($path);
    }
}
