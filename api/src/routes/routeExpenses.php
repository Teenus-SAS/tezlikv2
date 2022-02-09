<?php

use tezlikv2\dao\ExpensesDao;

$expensesDao = new ExpensesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/expensesDistribution', function (Request $request, Response $response, $args) use ($expensesDao) {
    $expensesDistribution = $expensesDao->findAllexpensesByCompany();
    $response->getBody()->write(json_encode($expensesDistribution, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
