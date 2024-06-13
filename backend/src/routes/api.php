<?php
require_once __DIR__ . '/../controllers/TaskController.php';
require_once __DIR__ . '/../controllers/test/subTest/TestController.php';

$routes = [
    'prefixes' => [
        'tasks' => '/controllers/TaskController',
        'tests' => '/controllers/test/subTest/TestController',
    ],

    'groups' => [
        'TaskController' => [
            'list' => ['GET', 'getAllTasks'],
            'add' => ['POST', 'addTask'],
            'edit' => ['PUT', 'updateTask'],
            'delete' => ['DELETE', 'deleteTask'],
        ],

        'TestController' => [
            'list' => ['GET', 'test'],
        ],
    ],
];

return $routes;
