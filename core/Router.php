<?php

namespace Core;

use Views\Error404Page;
use ReflectionClass;

class Router
{  
    // On construction: gather all possible routes using class reflection
    // where a route = a class estending the Route class
    private array $routes;
    public function __construct()
    {

        // Find all extensions of Route
        $this->routes = [];
        foreach (get_declared_classes() as $className) {

            // 3. Use Reflection to check inheritance safely
            $reflection = new ReflectionClass($className);

            // Ensure it extends the parent, isn't the parent itself, and can be instantiated
            if (
                $reflection->isSubclassOf('Core\Route') &&
                !$reflection->isAbstract() &&
                !$reflection->isInterface()
            ) {
                // 4. Instantiate the class (assumes a constructor with no mandatory arguments)
                $this->routes[] = $reflection->newInstance();
            }
        }

        // Dispatch to route
        $this->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    // Actual route selection
    public function dispatch(string $requestMethod, string $uri): void
    {
        // Strip query strings
        $path = parse_url($uri, PHP_URL_PATH);

        // Loop through routes
        foreach ($this->routes as $route) {
            // If verb differs, return early
            if (strtoupper($route->method) !== strtoupper($requestMethod)) {
                continue;
            }

            // Else attempt to match path
            $params = $route->attempt($path);
            if ($params !== null) {
                // If path matches, log and go (with extracted parameters)
                __log("{$requestMethod} {$uri} is recognized as route '{$route->name}'.");
                $route->go($params);
                return;
            }
        }

        // Fallback: nothing matched, so return 404
        __log("404 response on {$requestMethod} {$uri}");
        echo (new Error404Page())->Render();
    }

    
}