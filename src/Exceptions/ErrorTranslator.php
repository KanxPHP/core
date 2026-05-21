<?php

namespace KanxPHP\Core\Exceptions;

use KanxPHP\Core\Exceptions\IpaasException;

class ErrorTranslator
{
    /**
     * Translates third-party API rejections into standardized iPaaS exceptions.
     * Operates parameter-driven in-memory to ensure zero host-side storage dependencies.
     * 
     * @param string $platform e.g., 'woocommerce'
     * @param string $version e.g., 'v1'
     * @param int $httpCode The native HTTP status code returned from cURL
     * @param string $rawResponseBody The unmodified body string block returned from the upstream host
     */
    public static function translateUpstreamFailure(
        string $platform,
        string $version,
        int $httpCode,
        string $rawResponseBody
    ): IpaasException {
        
        // Handle physical network transit drops or empty server buffers first
        if ($httpCode === 0 || empty(trim($rawResponseBody))) {
            return new IpaasException(
                'NETWORK_TRANSIT_FAILURE',
                'The target system instance is completely unreachable or dropped the connection pipeline socket.',
                503
            );
        }

        $configRoot = getcwd() . '/config/connectors/';
        $dictionaryPath = "{$configRoot}{$platform}/{$version}/errors.json";

        if (file_exists($dictionaryPath)) {
            $dictionary = json_decode((string)file_get_contents($dictionaryPath), true);
            $nativeError = json_decode($rawResponseBody, true);

            // Extract the platform's unique native internal error token identifier string
            $nativeCodeKey = $nativeError['code'] ?? ($nativeError['errors'][0]['code'] ?? '');

            if (!empty($nativeCodeKey) && isset($dictionary[$nativeCodeKey])) {
                $mappedRule = $dictionary[$nativeCodeKey];
                return new IpaasException(
                    $mappedRule['ipaas_error_code'],
                    $mappedRule['user_message'],
                    $mappedRule['http_status'],
                    ['native_upstream_message' => $nativeError['message'] ?? ($nativeError['errors'][0]['message'] ?? '')]
                );
            }
        }

        // Generic fallback profile boundary if the incoming error state string is unrecognized
        return new IpaasException(
            'UNRECOGNIZED_PLATFORM_ERROR',
            'An unexpected error occurred during execution processing on the upstream storefront application.',
            $httpCode,
            ['raw_upstream_payload' => substr($rawResponseBody, 0, 400)]
        );
    }
}