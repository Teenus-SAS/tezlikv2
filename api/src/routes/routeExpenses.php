<?php

use tezlikv2\dao\ExpensesDao;

$expensesDao = new ExpensesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/expenses', function (Request $request, Response $response, $args) use ($expensesDao) {
    $expenses = $expensesDao->findAllExpensesByCompany();
    $response->getBody()->write(json_encode($expenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/expensesDistribution', function (Request $request, Response $response, $args) use ($expensesDao) {
    $expensesDistribution = $expensesDao->findAllExpensesDistributionByCompany();
    $response->getBody()->write(json_encode($expensesDistribution, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
