<?php

try {
    $routes = require_once __DIR__ . '/src/routes/api.php';

    $method = $_SERVER['REQUEST_METHOD'];

    $request_uri = $_SERVER['REQUEST_URI'];
    $url_parts = explode('/', trim($request_uri, '/'));
    $prefix = $url_parts[0];
    $path = $url_parts[1] ?? '';

    header('Content-type: application/json; charset=utf-8');

    if (isset($routes['prefixes'][$prefix])) {
        $controllerPath = $routes['prefixes'][$prefix];
        $controllerPath = __DIR__ . '/src/' . $controllerPath . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;

            $controllerName = basename($controllerPath, '.php');
            if (isset($routes['groups'][$controllerName][$path])) {
                $methodName = $routes['groups'][$controllerName][$path][1];
                $allowedMethod = $routes['groups'][$controllerName][$path][0];

                if ($method === $allowedMethod) {
                    require_once $controllerPath;

                    $controllerPath = str_replace('/', '\\', $routes['prefixes'][$prefix]);

                    $controllerClass = new $controllerPath();
                    $x = $controllerClass->$methodName();

                    echo $x;
                } else {
                    echo json_encode([
                        'success' => false,
                        'status' => 405,
                        'message' => "Method not allowed"
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'status' => 404,
                    'message' => "Route not found"
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'status' => 404,
                'message' => "Controller not found"
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'status' => 404,
            'message' => "Route Prefix not found"
        ]);
    }


} catch (Exception $exception) {
    echo $exception->getMessage();
}
