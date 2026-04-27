<?php
namespace KanxPHP\Core;

use KanxPHP\Core\Exceptions\IntegrityException;

class SafeString 
{
    /** Securely hashes passwords using Argon2id */
    public static function hash(string $password): string 
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        if ($hash === false) {
            throw new IntegrityException("Secure hash generation failed.");
        }
        return $hash;
    }

    /** Multi-byte safe string truncation */
    public static function limit(string $value, int $limit = 100): string 
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return mb_strimwidth($value, 0, $limit, '...', 'UTF-8');
    }
}