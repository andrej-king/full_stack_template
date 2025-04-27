<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Get string env value from one of sources: environment variable, $_SERVER, $_ENV
 *
 * @param string $name
 * @param string|null $default
 *
 * @return string
 */
if (!function_exists(__NAMESPACE__ . '\env')) {
    function env(string $name, ?string $default = null): string
    {
        $searchFunctions = [
            fn(string $name): ?string => getenv($name) !== false ? (string)getenv($name) : null,
            fn(string $name): ?string => array_key_exists($name, $_SERVER) ? (string)$_SERVER[$name] : null,
            fn(string $name): ?string => array_key_exists($name, $_ENV) ? (string)$_ENV[$name] : null,
        ];

        // search value
        foreach ($searchFunctions as $searchFunction) {
            $value = $searchFunction($name);

            if ($value !== null) {
                return $value;
            }
        }

        // search file
        foreach ($searchFunctions as $searchFunction) {
            $file = $searchFunction($name . '_FILE');

            if ($file !== null) {
                $content = file_get_contents($file);

                if ($content === false) {
                    throw new RuntimeException("Unable to open '{$file}' file");
                }

                return trim($content);
            }
        }

        if ($default !== null) {
            return $default;
        }

        throw new RuntimeException("Undefined env {$name}");
    }
}
