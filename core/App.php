<?php

namespace Core;

class App 
{
    // App registry
    // Register services which can be called afterwards
    // Not IDE friendly, but works in a pinch
    private static array $registry = [];

    public static function register(string $key, object|array $instance)
    {
        self::$registry[$key] = $instance;
    }

    public static function service(string $key)
    {
        if (!isset(self::$registry[$key])) {
            throw new \Exception("Service '{$key}' has not been registered.");
        }
        return self::$registry[$key];
    }
}
