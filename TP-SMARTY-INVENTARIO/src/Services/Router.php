<?php

namespace App\Services;

use App\Services\AuthService; // Add this use statement

class Router
{
    protected array $routes = [];
    protected array $middleware = [];
    protected string $basePath = '';
    protected AuthService $authService; // Add this property

    public function __construct(AuthService $authService, string $basePath = '') // Modify constructor
    {
    $this->basePath = $basePath;
        $this->authService = $authService; // Assign authService
    }

    public function get(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    protected function addRoute(string $method, string $path, callable $handler, array $middleware): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function addMiddleware(string $key, callable $handler): void
    {
        $this->middleware[$key] = $handler;
    }

    public function dispatch(\Smarty $smarty): void
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $path = parse_url($requestUri, PHP_URL_PATH);
        $route = substr($path, strlen($this->basePath));
        $route = strtok($route, '?');

        if (!empty($route) && $route[0] !== '/') {
            $route = '/' . $route;
        }

        if (empty($route)) {
            $route = '/';
        }

        error_log("Dispatching Route: " . $route . " Method: " . $requestMethod);

        foreach ($this->routes[$requestMethod] ?? [] as $pattern => $routeConfig) {
            $regex = '#^' . preg_replace('#/{([a-zA-Z0-9_]+)}#', '/(?P<$1>[^/]+)', $pattern) . '$#';

            if (preg_match($regex, $route, $matches)) {
                error_log("Route matched: Pattern='{$pattern}', Matches=" . print_r($matches, true));

                // Execute middleware
                foreach ($routeConfig['middleware'] as $mwKey) {
                    if (isset($this->middleware[$mwKey])) {
                        // Pass authService to middleware handler
                        $this->middleware[$mwKey]($this->authService);
                    } else {
                        error_log("Middleware '{$mwKey}' not found.");
                        // Handle missing middleware error
                        header("HTTP/1.0 500 Internal Server Error");
                        $smarty->assign('title', '500 Internal Server Error');
                        $smarty->display('404.tpl');
                        exit();
                    }
                }

                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array($routeConfig['handler'], $params);
                return;
            }
        }

        // No route matched
        header("HTTP/1.0 404 Not Found");
        $smarty->assign('title', '404 Not Found');
        $smarty->display('404.tpl');
    }
}
