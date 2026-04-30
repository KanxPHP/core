<?php

namespace KanxPHP\Core;

use KanxPHP\Core\Exceptions\IntegrityException;

class SafePassword
{
    /**
     * Securely hashes a password using Argon2id.
     */
    public static function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        
        if ($hash === false) {
            throw new IntegrityException("Secure hash generation failed. Check server Argon2 support.");
        }
        
        return $hash;
    }

    /**
     * Verifies a password against a hash.
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Determines if a given hash was created using the current 
     * security standards (Argon2id with default cost).
     * 
     * Usage: If this returns true during login, you should re-hash 
     * the user's password and update the database.
     */
    public static function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID);
    }
}