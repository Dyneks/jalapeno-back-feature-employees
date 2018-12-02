<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// PDO database library 
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";charset=UTF8;dbname=" . $settings['dbname'],
        $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
//klientu posistemes
$container['ReservationController'] = function($c)
{
    return new App\Controllers\ReservationController($c);
};

$container['OrderController'] = function($c)
{
    return new App\Controllers\OrderController($c);
};

$container['TableController'] = function($c)
{
    return new App\Controllers\TableController($c);
};

$container['PaymentController'] = function($c)
{
    return new App\Controllers\PaymentController($c);
};

$container['RatingController'] = function($c)
{
    return new App\Controllers\RatingController($c);
};
//darbuotoju posistemes
$container['EmployeeController'] = function($c)
{
    return new App\Controllers\EmployeeController($c);
};
$container['CandidateController'] = function($c)
{
    return new App\Controllers\CandidateController($c);
};
$container['ReportController'] = function($c)
{
    return new App\Controllers\ReportController($c);
};
