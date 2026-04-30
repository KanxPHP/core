<?php

namespace KanxPHP\Core;

class SafeCurl {
    /**
     * Executes a secure GET request to fetch headers or content.
     * Prevents access to internal/private IP ranges.
     */
    public static function fetch(string $url, bool $headersOnly = true) {
        if (!self::isSafeUrl($url)) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3); // Prevent redirect loops
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, $headersOnly);
        
        if ($headersOnly) {
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($headersOnly && $response) {
            return self::parseHeaders($response);
        }

        return $response;
    }

    /**
     * Validates that the URL is public and not a private/local IP.
     */
    private static function isSafeUrl(string $url): bool {
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) return false;

        $host = $parts['host'];
        $ip = gethostbyname($host);

        // Block private, reserved, and local ranges
        $blockedRanges = [
            '127.0.0.0/8',    // Localhost
            '10.0.0.0/8',     // Private
            '172.16.0.0/12',  // Private
            '192.168.0.0/16', // Private
            '169.254.0.0/16', // AWS/Metadata
            '0.0.0.0/8'
        ];

        foreach ($blockedRanges as $range) {
            if (self::ipInRange($ip, $range)) return false;
        }

        return true;
    }

    private static function ipInRange($ip, $range): bool {
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        return ($ip & $mask) == ($subnet & $mask);
    }

    private static function parseHeaders(string $headerContent): array {
        $headers = [];
        foreach (explode("\r\n", $headerContent) as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $headers[trim($key)] = trim($value);
            }
        }
        return $headers;
    }
}