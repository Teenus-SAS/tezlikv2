<?php

use tezlikv2\dao\ProcessDao;

$processDao = new ProcessDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/process', function (Request $request, Response $response, $args) use ($processDao) {
    session_start();
    /* $id_company = $_SESSION['empresas_id_empresas']; */
    $process = $processDao->findAllProcessByCompany(44);
    $response->getBody()->write(json_encode($process, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
