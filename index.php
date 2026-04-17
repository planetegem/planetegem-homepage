<?php

// If debug mode is enabled, logs are kept for every step taken
$debug_mode = true;

// Preload logging function
require_once('php/core/logger.php');
if ($debug_mode) $message_log("New debug session started");

// Important class files
require_once('php/views/view.php');

// Require all relevant php files
require_once('php/core/loader.php');

// This will check the url and detrmine which page needs to be built
require_once('php/core/router.php');

?>