<?php

use tezlikv2\dao\FactoryLoadDao;

$factoryloadDao = new FactoryLoadDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/factoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao) {
    session_start();
    $id_company = $_SESSION['empresas_id_empresas'];
    $machines = $factoryloadDao->findAllFactoryLoadByCompany($id_company);
    $response->getBody()->write(json_encode($machines, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
