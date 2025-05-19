<?php
class Router {
    private $routes = [];
    
    public function addRoute($url, $handler) {
        $this->routes[$url] = $handler;
    }
    
    public function route() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/');
        
        if (empty($url)) {
            $url = '/';
        }
        
        if (array_key_exists($url, $this->routes)) {
            $handler = $this->routes[$url];
            list($controller, $method) = explode('@', $handler);
            
            require_once "controllers/{$controller}.php";
            $controllerInstance = new $controller();
            $controllerInstance->$method();
        } else {
            header("HTTP/1.0 404 Not Found");
            include 'views/404.php';
            exit();
        }
    }
} 