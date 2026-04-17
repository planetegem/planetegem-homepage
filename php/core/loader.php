<?php

// Load all php files in php folder
$directory = "php/";

// If directory is empty, throw exception
if (!is_dir($directory))
    die("Error: Directory not found - " . $directory);

// Iterate through all files recursively
$directoryIterator = new RecursiveDirectoryIterator($directory);
$filter = new RecursiveCallbackFilterIterator(
    $directoryIterator,
    function ($current, $key, $iterator) {
        // Apply filter: ignore all files in the 'core' directory, which is loaded seperately
        if ($current->isDir() && $current->getFilename() === 'core') {
            return false;
        }
        return true;
    }
);

// Start actual recursion
$iterator = new RecursiveIteratorIterator($filter);
foreach($iterator as $file){
    if ($file->isDir()) continue;

    // Only include php files
    if ($file->isFile() && $file->getExtension() === 'php' && !str_ends_with($file->getPathName(), '.view.php')) {
        if($debug_mode) txt_log("Included {$file->getPathName()}");
        require_once($file->getPathName());
    }
}