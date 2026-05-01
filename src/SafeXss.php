<?php

namespace KanxPHP\Core;

/**
 * SafeXss: The Defensive Shield against Script Injection.
 */
class SafeXss
{
    /**
     * Deep Clean: Removes all tags and prevents "Polyglot" attacks.
     * Ideal for usernames, slugs, and plain-text fields.
     */
    public static function clean(string $data): string
    {
        // Remove null bytes
        $data = str_replace(chr(0), '', $data);
        
        // RAD Fix: Remove the contents of script and style tags entirely
        $data = preg_replace('/<(script|style)\b[^>]*>([\s\S]*?)<\/\1>/i', '', $data);
        
        // Strip remaining tags and encode
        return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }


    /**
     * Attribute Shield: Specifically cleans data meant for HTML attributes.
     * Prevents: <div title="[USER_INPUT]"> escaping via quotes.
     */
    public static function cleanAttr(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Pattern Watcher: Identifies if a string contains XSS signatures.
     * Useful for the "XSS Threat Scorer" micro-app.
     */
    public static function detect(string $data): bool
    {
        $patterns = [
            '/<script/i',
            '/on\w+\s*=/i',       // onclick, onload, etc.
            '/javascript:/i',
            '/expression\s*\(/i', // Old IE attacks
            '/<iframe/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $data)) {
                return true;
            }
        }

        return false;
    }
}
