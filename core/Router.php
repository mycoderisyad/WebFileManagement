<?php
class Router {
    private $routes = [];
    
    public function addRoute($url, $handler) {
        $this->routes[$url] = $handler;
    }
    
    public function route() {
        $url = $this->getCurrentUrl();
        
        if (isset($this->routes[$url])) {
            $this->executeRoute($this->routes[$url]);
        } else {
            $this->handleNotFound();
        }
    }
    
    private function getCurrentUrl()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/');
        return empty($url) ? '/' : $url;
    }
    
    private function executeRoute($handler)
    {
        [$controller, $method] = explode('@', $handler);
        
        require_once "controllers/{$controller}.php";
        $instance = new $controller();
        $instance->$method();
    }
    
    private function handleNotFound()
    {
        header("HTTP/1.0 404 Not Found");
        include 'views/not_found_page.php';
        exit();
    }
} 