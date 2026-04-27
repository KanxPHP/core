<?php
namespace KanxPHP\Core;

use KanxPHP\Core\Exceptions\IntegrityException;

class SafeString 
{
    /** 
     * Securely hashes passwords using Argon2id 
     */
    public static function hash(string $password): string 
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        if ($hash === false) {
            throw new IntegrityException("Secure hash generation failed.");
        }
        return $hash;
    }

    /** 
     * Verifies a password against a hash
     * Gateway: Simple true/false return.
     * Value-Add: Timing-attack safe by default via native password_verify.
     */
    public static function verify(string $password, string $hash): bool 
    {
        return password_verify($password, $hash);
    }

    /** 
     * Multi-byte safe string truncation 
     */
    public static function limit(string $value, int $limit = 100): string 
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return mb_strimwidth($value, 0, $limit, '...', 'UTF-8');
    }
}