<?php

// Select main logging function
$message_log = 'txt_log';

// Log function 1: save logs to .txt file
function txt_log($message)
{
    // Filter out some noise (requests for favico etc)
    $requestPath = $_SERVER['REQUEST_URI'] ?? '';
    $ignoredPaths = [
        '/favicon.ico',
        '/.well-known/appspecific/com.chrome.devtools.json'
    ];

    foreach ($ignoredPaths as $ignored) {
        if (str_starts_with($requestPath, $ignored)) {
            return; // Stop debugger if detected noise path
        }
    }

    // Log message
    $processed_message = date("d-m-Y H:i:s") . " | " . $message . "\r\n";
    error_log($processed_message, 3, 'logs.txt');
}