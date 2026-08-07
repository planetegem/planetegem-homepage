<?php

namespace Core;

use ErrorException;

class Environment
{
    // Internal props tracker
    private array $props = [];

    // Get env values in safe way
    public function __get(string $name){
        return $this->props[$name] ?? throw new ErrorException("Environment property {$name} was not found.");
    }

    // Helper function to cast values to proper types
    private function castValue(string $value): mixed
    {
        return match (strtolower($value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            default => is_numeric($value) ? $value + 0 : $value,
        };
    }

    // Construct handles the main logic
    // Load env file, set to props, create Environment object
    public function __construct($env_path)
    {
        // Return early if no .env file 
        if (!file_exists($env_path))
            return;

        // Read the .env file
        $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Loop through .env lines and parse them
        foreach ($lines as $line) {
            // Skip comments (line starts with #)
            if (str_starts_with(trim($line), '#'))
                continue;

            // Split line at first '='
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);

                // Trim strings before use (no whitespace, no quotes)
                $key = strtoupper(trim($key));
                $value = trim($value);
                $value = trim($value, '"\'');

                // Assign values to props
                $castValue = $this->castValue($value);
                $this->props[$key] = $castValue;

                // Set both system env and $_ENV superglobal (optional)
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }
}