<?php

use tezlikv2\dao\ActiveUsersDao;

$activeUsersDao = new ActiveUsersDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//USUARIOS ACTIVOS GENERAL
$app->get('/quantityAllUsers', function (Request $request, Response $response, $args) use ($activeUsersDao) {

    //NÚMERO DE USUARIOS ACTIVOS GENERAL
    $resp = $activeUsersDao->usersStatus();

    $response->getBody()->write(json_encode($resp, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
