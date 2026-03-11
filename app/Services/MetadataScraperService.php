<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MetadataScraperService
{
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    private const TIMEOUT = 10;

    /**
     * Scrape metadata from a URL using regex extraction.
     *
     * @param string $url
     * @return array
     */
    public function scrape(string $url): array
    {
        try {
            $response = Http::withOptions([
                'timeout' => self::TIMEOUT,
            ])
            ->withHeaders([
                'User-Agent' => self::USER_AGENT,
            ])
            ->get($url);

            if (!$response->successful()) {
                return $this->fallback($url);
            }

            $html = $response->body();

            return [
                'title' => $this->extractTitle($html),
                'description' => $this->extractDescription($html),
                'og_image_url' => $this->extractOgImage($html, $url),
                'site_name' => $this->extractSiteName($html, $url),
                'favicon_url' => $this->extractFavicon($html, $url),
            ];
        } catch (\Exception $e) {
            return $this->fallback($url);
        }
    }

    /**
     * Extract title from HTML (og:title → <title>).
     *
     * @param string $html
     * @return string|null
     */
    private function extractTitle(string $html): ?string
    {
        // Try og:title first
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }
        
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:title["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }

        // Fall back to <title>
        if (preg_match('/<title>([^<]+)<\/title>/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }

        return null;
    }

    /**
     * Extract description from HTML (og:description → meta description).
     *
     * @param string $html
     * @return string|null
     */
    private function extractDescription(string $html): ?string
    {
        // Try og:description first
        if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }
        
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:description["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }

        // Fall back to meta description
        if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }
        
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+name=["\']description["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }

        return null;
    }

    /**
     * Extract OG image URL from HTML, resolve relative URLs.
     *
     * @param string $html
     * @param string $baseUrl
     * @return string|null
     */
    private function extractOgImage(string $html, string $baseUrl): ?string
    {
        $imageUrl = null;

        if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            $imageUrl = $matches[1];
        } elseif (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $matches)) {
            $imageUrl = $matches[1];
        }

        if ($imageUrl) {
            return $this->resolveUrl($this->clean($imageUrl), $baseUrl);
        }

        return null;
    }

    /**
     * Extract site name from HTML (og:site_name).
     *
     * @param string $html
     * @param string $url
     * @return string|null
     */
    private function extractSiteName(string $html, string $url): ?string
    {
        if (preg_match('/<meta[^>]+property=["\']og:site_name["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }
        
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:site_name["\']/i', $html, $matches)) {
            return $this->clean($matches[1]);
        }

        // Fallback to hostname
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Extract favicon URL from HTML, resolve relative URLs.
     *
     * @param string $html
     * @param string $baseUrl
     * @return string|null
     */
    private function extractFavicon(string $html, string $baseUrl): ?string
    {
        $faviconUrl = null;

        // Look for various favicon patterns
        $patterns = [
            // Standard icon
            '/<link[^>]+rel=["\']icon["\'][^>]+href=["\']([^"\']+)["\']/i',
            '/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']icon["\']/i',
            // Shortcut icon
            '/<link[^>]+rel=["\']shortcut icon["\'][^>]+href=["\']([^"\']+)["\']/i',
            '/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']shortcut icon["\']/i',
            // Apple touch icon as fallback
            '/<link[^>]+rel=["\']apple-touch-icon["\'][^>]+href=["\']([^"\']+)["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $faviconUrl = $matches[1];
                break;
            }
        }

        if ($faviconUrl) {
            return $this->resolveUrl($this->clean($faviconUrl), $baseUrl);
        }

        // Fallback to /favicon.ico
        return $this->resolveUrl('/favicon.ico', $baseUrl);
    }

    /**
     * Resolve a relative URL against a base URL.
     *
     * @param string $url
     * @param string $baseUrl
     * @return string
     */
    private function resolveUrl(string $url, string $baseUrl): string
    {
        // Already absolute
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        $parsedBase = parse_url($baseUrl);
        $scheme = $parsedBase['scheme'] ?? 'https';
        $host = $parsedBase['host'] ?? '';

        if (str_starts_with($url, '//')) {
            return $scheme . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        // Relative path
        $basePath = $parsedBase['path'] ?? '/';
        $basePath = dirname($basePath);
        if ($basePath === '.') {
            $basePath = '/';
        }
        if (!str_ends_with($basePath, '/')) {
            $basePath .= '/';
        }

        return $scheme . '://' . $host . $basePath . $url;
    }

    /**
     * Clean extracted text: html_entity_decode and trim.
     *
     * @param string $text
     * @return string
     */
    private function clean(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim($text);
    }

    /**
     * Fallback values when scraping fails.
     *
     * @param string $url
     * @return array
     */
    private function fallback(string $url): array
    {
        $hostname = parse_url($url, PHP_URL_HOST);

        return [
            'title' => null,
            'description' => null,
            'og_image_url' => null,
            'site_name' => $hostname,
            'favicon_url' => 'https://' . $hostname . '/favicon.ico',
        ];
    }
}
