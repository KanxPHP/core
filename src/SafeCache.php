<?php

namespace KanxPHP\Core;

class SafeCache 
{
    private static string $storage = __DIR__ . '/../../storage/cache';

    /**
     * Store data using the OpCache-friendly "var_export" method.
     */
    public static function set(string $key, $val, string $timeout = '+24 hours'): void 
    {
        if (!is_dir(self::$storage)) mkdir(self::$storage, 0755, true);

        $expire = strtotime($timeout);
        $expireExport = var_export($expire, true);
        $valExport = var_export($val, true);

        // Fix for HHVM and standard objects
        $valExport = str_replace('stdClass::__set_state', '(object)', $valExport);
        
        $content = "<?php \$expire = $expireExport; \$val = $valExport;";
        $file = self::$storage . '/' . md5($key) . '.php';
        
        // Atomic write
        file_put_contents($file . '.tmp', $content, LOCK_EX);
        rename($file . '.tmp', $file);
    }

    /**
     * Retrieve data using the include hack.
     */
    public static function get(string $key) 
    {
        $file = self::$storage . '/' . md5($key) . '.php';

        if (!file_exists($file)) return null;

        // The @include hack: injects $expire and $val into local scope
        @include $file;

        if (isset($expire) && $expire < time()) {
            @unlink($file); // Clean up expired cache
            return null;
        }

        return $val ?? null;
    }
}
