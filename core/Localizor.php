<?php

namespace Core;

class Localizor
{
    // Path where all localization files live: set during bootstrap
    private string $path;

    // Current locale: path + locale form path to current localization files
    private string $locale;

    // Getter and setter for $locale
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }
    public function getLocale(string $locale)
    {
        return $this->locale;
    }

    // Constructor sets the default path and default locale
    public function __construct(string $path, string $locale)
    {
        $this->path = $path;
        $this->locale = $locale;
    }

    // File loading logic
    // Cache localization files to limit amount of fetching
    private array $loaded = [];
    private function loadFile(string $file, string $locale)
    {
        if (isset($this->loaded[$locale][$file]))
            return $this->loaded[$locale][$file];

        $filePath = "{$this->path}/{$locale}/{$file}.php";

        if (file_exists($filePath)) {
            $fileContent = include $filePath;
            $this->loaded[$locale][$file] = is_array($fileContent) ? $fileContent : [];
        } else {
            __log("Localization file not found: {$filePath}");
            $this->loaded[$locale][$file] = [];
        }

        return $this->loaded[$locale][$file];
    }

    // Array traversal logic: allow traversal through associative array with dot notation
    private function traverse(string $key, array $file, string $default) : string
    {
        $requestedValue = $file;
        foreach (explode('.', $key) as $segment) {
            if (is_array($requestedValue) && array_key_exists($segment, $requestedValue)) {
                $requestedValue = $requestedValue[$segment];
            } else {
                __log("Traversal of localization file failed at {$segment} for {$key}");
                return $default;
            }
        }
        if (is_array($requestedValue)){
            __log("Traversal of localization file ended at array for key {$key}");
            return $default;
        }
        return $requestedValue;
    }

    // Replace variables in returned string
    private function replace(string $line, array $replacements) : string {
        foreach ($replacements as $key => $value){
            $line = str_replace(':' . $key, $value, $line);
        }
        return $line;
    }

    // Main method to return strings from localization files
    public function get(string $key, array $replacements = []): string
    {
        $locale = $this->locale;

        $parts = explode('.', $key, 2);
        $file = $parts[0];
        $itemKey = $parts[1] ?? null;

        // Return early if no key
        if ($itemKey === null){
            __log("No item key for localization: {$key}");
            return $key;
        }

        $translations = $this->loadFile($file, $locale);
        $line = $this->traverse($itemKey, $translations, $key);

        return $this->replace($line, $replacements);
    }


}