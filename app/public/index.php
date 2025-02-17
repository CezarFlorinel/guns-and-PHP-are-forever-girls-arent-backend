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


$router->before('POST|PUT|DELETE', '/questionAndAnswers.*', 'checkJwtMiddleware');
$router->mount('/questionAndAnswers', function () use ($router) {
    $router->get('/', 'QuestionAndAnswerController@getAll');
    $router->post('/', 'QuestionAndAnswerController@create');
    $router->delete('/(\d+)', 'QuestionAndAnswerController@deleteQandA');
});



$router->post('/users/login', 'UserController@login'); // this route doesn't require jwt authentication

$router->before('GET|PUT|DELETE', '/user*', 'checkJwtMiddleware');
$router->mount('/user', function () use ($router) {
    $router->get('/get-all-users', 'UserController@getAll');
    $router->post('/', 'UserController@createUser');
    $router->put('/username/(.+)', 'UserController@updateUser');
    $router->put('/update-password', 'UserController@updatePassword');
    $router->delete('/(\d+)', 'UserController@deleteUser');
});

$router->before('POST|PUT|DELETE', '/modifications*', 'checkJwtMiddleware');
$router->mount('/modifications', function () use ($router) {
    $router->get('/', 'ModificationsController@getAll');
    $router->post('/', 'ModificationsController@create');
    $router->post('/(\d+)', 'ModificationsController@update');
    $router->delete('/(\d+)', 'ModificationsController@delete');
});

$router->get('/get-all-guns', 'GunController@getGunsToDisplayInGunsPage'); // used to bypass the jwt middleware
$router->before('GET|POST|PUT|DELETE', '/guns*', 'checkJwtMiddleware');
$router->mount('/guns', function () use ($router) {
    $router->get('/favourite-guns/(\d+)', 'GunController@getFavouriteGunsByUserID');
    $router->get('/favourite-guns/ids/(\d+)', 'GunController@getIdsOfFavouriteGuns');
    $router->get('/(\d+)', 'GunController@getGunById');
    $router->get('/owned-guns/(\d+)', 'GunController@getGunsOwnedByUser');
    $router->get('/gun-types', 'GunController@getTypesOfGuns');
    $router->post('/favourite-guns/(\d+)/(\d+)', 'GunController@addGunToFavourites');
    $router->post('/create', 'GunController@createGun');
    $router->post('/update/(\d+)', 'GunController@updateGun'); // put doesn't work with form-data  ?
    $router->delete('/favourite-guns/(\d+)/(\d+)', 'GunController@removeGunFromFavourites');
    $router->delete('/(\d+)', 'GunController@deleteGun');
});


















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