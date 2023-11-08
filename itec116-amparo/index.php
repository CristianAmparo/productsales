<?php
require_once "db.php";
require_once "Router.php";
require_once "ExpenseController.php";

header("Content-Type: application/json");

$router = new Router();

$router->get('/', 'ExpenseController@test');
$router->post('/expenses', 'ExpenseController@store');
$router->get('/expenses', 'ExpenseController@index');
$router->get('/expenses/{id}', 'ExpenseController@show');
$router->delete('/expenses/{id}', 'ExpenseController@destroy');
$router->get('/outgoing', 'ExpenseController@getSales');
$router->post('/outgoing', 'ExpenseController@outgoing');

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$router->handleRequest();
