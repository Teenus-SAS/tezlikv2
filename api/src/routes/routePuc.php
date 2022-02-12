<?php

use tezlikv2\dao\PucDao;

$pucDao = new PucDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/puc', function (Request $request, Response $response, $args) use ($pucDao) {
    $puc = $pucDao->findAllCountsPUC();
    $response->getBody()->write(json_encode($puc, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
