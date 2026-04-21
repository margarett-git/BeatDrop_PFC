<?php
ob_start();
session_start();

require_once 'config/db.php';
require_once 'config/autoload.php';

function route($uri) {
    $uri = trim($uri, '/');
    $parts = $uri === '' ? [] : explode('/', $uri);
    $params = [];

    if (isset($parts[0]) && $parts[0] === 'auth') {
        $controller = 'AuthController';
        $action = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : 'login';
        $params = array_slice($parts, 2);
    } elseif (isset($parts[0]) && $parts[0] === 'admin') {
        if (isset($parts[1]) && $parts[1] !== '' && $parts[1] !== 'dashboard') {
            $controller = 'Admin' . ucfirst($parts[1]) . 'Controller';
            $action = isset($parts[2]) && $parts[2] !== '' ? $parts[2] : 'index';
            $params = array_slice($parts, 3);
        } else {
            $controller = 'AdminController';
            $action = 'dashboard';
        }
    } elseif (isset($parts[0]) && $parts[0] !== '') {
        $controller = ucfirst($parts[0]) . 'Controller';
        $action = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : 'index';
        $params = array_slice($parts, 2);
    } else {
        $controller = 'HomeController';
        $action = 'index';
    }

    return [$controller, $action, $params];
}

$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$requestUri = parse_url($requestUri, PHP_URL_PATH) ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$basePath = dirname($scriptName);
$uri = $requestUri;

if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = trim($uri, '/');
list($controllerName, $action, $params) = route($uri);

if (!class_exists($controllerName)) {
    http_response_code(404);
    echo 'Página no encontrada';
    exit;
}

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Página no encontrada';
    exit;
}

call_user_func_array([$controller, $action], $params);
ob_end_flush();