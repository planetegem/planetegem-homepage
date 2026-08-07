<?php
namespace Core;

abstract class Route {
    public string $path = '/';
    public string $method = 'GET';
    public string $name = 'Home';

    abstract public function go(array $params) : void;

    public function attempt(string $requestPath) : ?array {

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $this->path);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return null;
    }
}