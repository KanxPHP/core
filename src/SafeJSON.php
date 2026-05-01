<?php

namespace KanxPHP\Core;

use KanxPHP\Core\Exceptions\IntegrityException;

class SafeJSON
{
    /**
     * Decodes a JSON string into an associative array.
     * 
     * @param string $json The JSON string to parse
     * @param int $depth Maximum nesting depth
     * @return array
     * @throws IntegrityException If parsing fails or result is not an array
     */
    public static function parse(string $json, int $depth = 512): array
    {
        $data = json_decode($json, true, $depth, JSON_THROW_ON_ERROR);

        // Ensure we always return an array to maintain type integrity
        if (!is_array($data)) {
            throw new IntegrityException(
                "JSON parsed successfully but did not return an array structure.",
                0,    // $code
                null, // $previous
                ['type_received' => gettype($data)] // $context
            );
        }

        return $data;
    }

    /**
     * Encodes data into a JSON string with safe defaults.
     * 
     * @param mixed $value The data to encode
     * @return string
     * @throws IntegrityException
     */
    public static function encode($value): string
    {
        try {
            // Force UTF-8 and pretty print for developer-friendly logs
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\JsonException $e) {
            throw new IntegrityException(
                "Failed to encode data to JSON: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Returns success
     * 
     * @param array $data
     * @param int $code
     * @return string
     */
    public static function success(array $data, int $code = 200): string 
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        
        return self::encode([
            'status' => 'success',
            'data' => $data,
            'meta' => [
                'method'         => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN', // The new indicator
                'execution_time' => round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 4) . 's',
                'timestamp'      => time(),
                'request_id'     => uniqid('knx_', true) // Added for easier log tracking
            ]
        ]);
    }

    /**
     * Returns error
     * 
     * @param string $message
     * @param array $details
     * @param int $code
     * @return string
     */
    public static function error(string $message, array $details = [], int $code = 400): string 
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        return self::encode([
            'status' => 'error',
            'message' => $message,
            'details' => $details,
            'meta' => [
                'timestamp' => time()
            ]
        ]);
    }
    
}