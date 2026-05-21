<?php

namespace KanxPHP\Core;

use KanxPHP\Core\Config;
use KanxPHP\Core\SafeJSON;

class SafeConnector
{
    /**
     * UNIVERSAL EXTRACTOR (A ➔ iPaaS)
     * Ingests any native data structure string and maps it dynamically into your 
     * clean, universal standard format completely in-memory using dot notation rules.
     */
    public static function extract(string $rawJsonText, array $mappingRules): array
    {
        // Leverage your existing SafeJSON helper structure to decode text payloads safely
        $nativeData =  SafeJSON::parse($rawJsonText);
        if (!is_array($nativeData)) {
            return [];
        }

        $standardizedOutput = [];

        foreach ($mappingRules as $ipaasTargetRule => $nativePath) {
            $typeCast = null;
            $ipaasStandardKey = $ipaasTargetRule;

            // Detect embedded format definitions modifiers (e.g. "standard_price:float")
            if (strpos($ipaasTargetRule, ':') !== false) {
                list($ipaasStandardKey, $typeCast) = explode(':', $ipaasTargetRule, 2);
            }

            // Extract the property using our dot-notation nested array resolver loop
            $value = self::resolveDotPath($nativeData, (string)$nativePath);

            // Execute dynamic data type coercion on the volatile trace memory register
            if ($value !== null && $typeCast !== null) {
                switch ($typeCast) {
                    case 'float':  $value = (float)$value; break;
                    case 'int':    $value = (int)$value; break;
                    case 'string': $value = (string)$value; break;
                    case 'bool':   $value = (bool)$value; break;
                }
            }

            $standardizedOutput[$ipaasStandardKey] = $value;
        }

        return $standardizedOutput;
    }

    /**
     * UNIVERSAL GENERATOR (iPaaS ➔ B)
     * Takes a clean internal standard array and packs it back into ANY complex, 
     * nested platform payload required by destination endpoints.
     */
    public static function inject(array $standardIpaasData, array $pushRules): array
    {
        $nativeOutputPayload = [];

        foreach ($pushRules as $nativeTargetRule => $ipaasStandardKey) {
            $typeCast = null;
            $nativeKeyPath = $nativeTargetRule;

            // Detect type parameters formatting constraints (e.g. "regular_price:string")
            if (strpos($nativeTargetRule, ':') !== false) {
                list($nativeKeyPath, $typeCast) = explode(':', $nativeTargetRule, 2);
            }

            // Isolate current metric property context value
            $value = $standardIpaasData[$ipaasStandardKey] ?? null;

            // Force types dynamically based on config instructions
            if ($value !== null && $typeCast !== null) {
                switch ($typeCast) {
                    case 'string': $value = (string)$value; break;
                    case 'int':    $value = (int)$value; break;
                    case 'float':  $value = (float)$value; break;
                    case 'bool':   $value = (bool)$value; break;
                }
            }

            // Build multi-dimensional nested target arrays recursively (e.g. "billing.address.city")
            self::assignNestedValue($nativeOutputPayload, (string)$nativeKeyPath, $value);
        }

        return $nativeOutputPayload;
    }

    /**
     * Helper: Walks down multi-dimensional array structures using dot paths.
     */
    private static function resolveDotPath(array $data, string $path)
    {
        if (strpos($path, '.') === false) {
            return $data[$path] ?? null;
        }

        foreach (explode('.', $path) as $segment) {
            if (is_array($data) && isset($data[$segment])) {
                $data = $data[$segment];
            } else {
                return null;
            }
        }

        return $data;
    }

    /**
     * Helper: Dynamically constructs deep object tree references in system RAM.
     */
    private static function assignNestedValue(array &$arr, string $path, $value): void
    {
        $keys = explode('.', $path);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($arr[$key]) || !is_array($arr[$key])) {
                $arr[$key] = [];
            }
            $arr = &$arr[$key];
        }

        $arr[array_shift($keys)] = $value;
    }
}