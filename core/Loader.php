<?php

namespace Core;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Loader
{
    

    public function loadDirectory($directory)
    {

        // If directory is invalid: log error and return
        if (!is_dir($directory)) {
            __log("Error: Directory not found - " . $directory);
            return;
        }

        // Iterate through all files recursively
        $directoryIterator = new RecursiveDirectoryIterator($directory);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        foreach ($iterator as $file) {
            if ($file->isDir())
                continue;

            // Only include php files
            if ($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathName(), '.view.php')) {
                require_once($file->getPathName());
            }
        }

    }
}