<?php

namespace KanxPHP\Core;

/**
 * SafeProxy: The Traffic Obfuscator
 * Manages rotation, health, and auth for outbound requests.
 */
class SafeProxy 
{
    private static array $proxies = [];
    private static string $pointerFile = __DIR__ . '/../../storage/proxy_pointer.txt';

    /**
     * Load proxies from .env or Array.
     * Expects: ['ip:port', 'user:pass@ip:port']
     */
    public static function load(array $list = []): void 
    {
        self::$proxies = $list;
    }

    /**
     * Get the next proxy in line (Round Robin).
     */
    public static function get(): ?string {
        if (empty(self::$proxies)) return null;

        $count = count(self::$proxies);
        
        // 1. Get the last index used from a tiny flat file
        $index = 0;
        if (file_exists(self::$pointerFile)) {
            $index = (int)file_get_contents(self::$pointerFile);
        }

        // 2. Select the proxy
        $proxy = self::$proxies[$index % $count];

        // 3. Increment and save the pointer for the NEXT request
        $nextIndex = ($index + 1) % $count;
        
        // Ensure storage directory exists
        if (!is_dir(dirname(self::$pointerFile))) {
            mkdir(dirname(self::$pointerFile), 0755, true);
        }
        
        file_put_contents(self::$pointerFile, $nextIndex);

        return $proxy;
    }

    /**
     * Health Check: Verifies if a proxy is still alive.
     * Useful for the "Proxy Scraper" micro-app logic.
     */
    public static function isAlive(string $proxy, int $timeout = 3): bool 
    {
        $parts = parse_url("http://" . $proxy);
        $host = $parts['host'] ?? $proxy;
        $port = $parts['port'] ?? 80;

        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
