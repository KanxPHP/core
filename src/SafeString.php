<?php

namespace KanxPHP\Core;

use KanxPHP\Core\Exceptions\IntegrityException;

class SafeString
{
    /**
     * Multi-byte safe string truncation.
     * Prevents cutting through UTF-8 characters which can corrupt data.
     */
    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return mb_strimwidth($value, 0, $limit, $end, 'UTF-8');
    }

    /**
     * Timing-attack safe string comparison.
     * Use this for comparing API keys, tokens, or hashes.
     */
    public static function equals(string $known, string $user): bool
    {
        return hash_equals($known, $user);
    }

    /**
     * Generates a cryptographically secure random string.
     * Essential for CSRF tokens, salts, or temporary secrets.
     */
    public static function random(int $length = 32): string
    {
        try {
            return bin2hex(random_bytes($length / 2));
        } catch (\Exception $e) {
            throw new IntegrityException("CSPRNG source failed: " . $e->getMessage());
        }
    }
}