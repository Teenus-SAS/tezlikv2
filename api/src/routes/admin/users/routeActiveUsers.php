<?php

use tezlikv2\dao\ActiveUsersDao;

$activeUsersDao = new ActiveUsersDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//USUARIOS ACTIVOS GENERAL
$app->post('/quantityUsers', function (Request $request, Response $response, $args) use ($activeUsersDao) {
    $dataQuantityUser = $request->getParsedBody();

    //NÚMERO DE USUARIOS ACTIVOS GENERAL
    $activeUsers = $activeUsersDao->usersStatus();
    $resp = $activeUsers;

    $response->getBody()->write(json_encode($resp, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
