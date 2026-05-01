<?php

namespace KanxPHP\Core;

class SafeCache 
{
    private static string $storagePath = '/../storage/cache';
    private static string $storage = '';

    /**
     * Store data using the OpCache-friendly "var_export" method.
     */
    public static function set(string $key, $val, string $timeout = '+24 hours'): void 
    {
        self::$storage = getcwd() . self::$storagePath;
     
        if (!is_dir(self::$storage)) {
            mkdir(self::$storage, 0755, true);
        }

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
        self::$storage = getcwd() . self::$storagePath;
     
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
