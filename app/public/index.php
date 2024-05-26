<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400'); // Cache for 1 day
    header('HTTP/1.1 200 OK');
    exit(0);
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/jwt_middleware.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// Define a group of routes that require JWT authentication
$router->before('GET|POST|PUT|DELETE', '/products.*', 'checkJwtMiddleware');
$router->before('GET|POST|PUT|DELETE', '/categories.*', 'checkJwtMiddleware');


// Define routes that do not require authentication
$router->post('/users/login', 'UserController@login');

// Define routes that require JWT authentication
$router->mount('/products', function () use ($router) {
    $router->get('/', 'ProductController@getAll');
    $router->get('/(\d+)', 'ProductController@getOne');
    $router->post('/', 'ProductController@create');
    $router->put('/(\d+)', 'ProductController@update');
    $router->delete('/(\d+)', 'ProductController@delete');
});

$router->mount('/categories', function () use ($router) {
    $router->get('/', 'CategoryController@getAll');
    $router->get('/(\d+)', 'CategoryController@getOne');
    $router->post('/', 'CategoryController@create');
    $router->put('/(\d+)', 'CategoryController@update');
    $router->delete('/(\d+)', 'CategoryController@delete');
});

// Run it!
$router->run();