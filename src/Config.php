<?php

namespace KanxPHP\Core;

/**
 * KanxPHP Config Utility
 * Handles environment detection and global settings.
 */
class Config
{
    /**
     * Detects if the current environment is Windows.
     * Useful for path resolution and CLI command formatting.
     */
    public static function isWindows(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    /**
     * Standardises directory separators based on the OS.
     */
    public static function fixPath(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Gets an environment variable or a default value.
     */
    public static function get(string $key, $default = null)
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}
