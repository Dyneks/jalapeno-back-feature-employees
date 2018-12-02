<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Enable lazy CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/rezervacijos_busenos', 'ReservationController:getAllStates');
$app->get('/rezervacijos_busenos/{id}', 'ReservationController:getState');

$app->get('/rezervacijos/{id}', 'ReservationController:get');
$app->post('/rezervacijos', 'ReservationController:create');
$app->delete('/rezervacijos/{id}', 'ReservationController:delete');

$app->get('/uzsakymo_busenos', 'OrderController:getAllStates');
$app->get('/uzsakymo_busenos/{id}', 'OrderController:getState');

$app->get('/staliukai', 'TableController:getAll');



$app->get('/darbuotojo_roles', 'EmployeeController:getAllStates');
$app->get('/darbuotojo_roles/{id}', 'EmployeeController:getState');

$app->get('/darbuotojai/{id}', 'EmployeeController:get');
$app->post('/darbuotojaiCreate', 'EmployeeController:create');
$app->delete('/darbuotojai/{id}', 'EmployeeController:delete');
$app->put('/redaguotiDarbuotoja/{id}', 'EmployeeController:update');


$app->get('/kandidatai/{id}', 'CandidateController:get');
$app->post('/kandidatai', 'CandidateController:create');

$app->put('/atrinktiKandidata/{id}', 'CandidateController:invite');







// Catch-all route to serve a 404 Not Found page if none of the routes match
// NOTE: make sure this route is defined last
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});