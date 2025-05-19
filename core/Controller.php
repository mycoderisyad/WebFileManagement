<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        include_once "views/{$view}.php";
    }
    
    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }
} 