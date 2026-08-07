<?php

// START SESSION
session_start();

// AUTOLOADER
// Autoload class file if class wasn't found
spl_autoload_register(function (string $class) {
    $path = __DIR__ . '/' . str_replace('\\', '/', lcfirst($class)) . '.php';
    if (file_exists($path)) { require_once $path; }
});

use Core\App;

// INSTANTIATE ENVIRONMENT
use Core\Environment;
App::register('ENV', new Environment('../.env'));

// INSTANTIATE LOGGER
use Core\Logger;
$logger = new Logger();

// Register as service with app
App::register('LOG', $logger);
// Helper function to quickly access main message method of logger
function __log(string $message){
    global $logger;
    $logger->message($message);
}

// INSTANTIATE LOCALIZER
use Core\Localizor;
$localizor = new Localizor('../localization', App::service('ENV')->DEFAULT_LOCALE);

// Register as service with app
App::register('LOCALE', $localizor);

// Helper function to quickly access main get method of localizor
function __(string $key, array $replacements = []){
    global $localizor;
    return $localizor->get($key, $replacements);
}

// INSTANTIATE API CLIENT
use Core\SysInvAdminClient;
$apiClient = new SysInvAdminClient();

// Register as service with App
App::register('API_CLIENT', $apiClient);

// Helper function to quickly retrieve API client
function __api(){
    global $apiClient;
    return $apiClient;
}

// PRELOAD ROUTES, VIEWS AND HELPERS
use Core\Loader;

$loader = new Loader();
$loader->loadDirectory('../routes');
$loader->loadDirectory('../views');
$loader->loadDirectory('../helpers');

// INSTANTIATE ROUTER
use Core\Router;
APP::register('ROUTER', new Router());
