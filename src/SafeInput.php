<?php

namespace KanxPHP\Core;

class SafeInput 
{
    private static ?array $payload = null;

    /**
     * Bootstraps the input, prioritizing JSON bodies (common in RapidAPI).
     */
    private static function init() 
    {
        if (self::$payload !== null) return;

        // Start with GET parameters
        self::$payload = $_GET;

        // Merge in JSON Body if it exists
        $json = file_get_contents('php://input');
        if (!empty($json)) {
            try {
                $body = SafeJSON::parse($json);
                self::$payload = array_merge(self::$payload, $body);
            } catch (\Exception $e) {
                // Ignore malformed JSON or handle via SafeJSON directly
            }
        }
    }

    /**
     * Get a sanitized value from any input source.
     */
    public static function get(string $key, $default = null) 
    {
        self::init();
        
        $value = self::$payload[$key] ?? $default;

        // Apply Global RAD Sanitisation
        if (is_string($value)) {
            $value = trim(strip_tags($value));
            // Future: Run through SqlGuard::check($value)
        }

        return $value;
    }

    /**
     * Return all captured and cleaned input.
     */
    public static function all(): array 
    {
        self::init();
        return self::$payload;
    }
}