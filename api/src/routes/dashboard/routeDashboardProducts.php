<?php

use tezlikv2\dao\DashboardDao;

$dashboardDao = new DashboardDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardExpenses', function (Request $request, Response $response, $args) use ($dashboardDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $expenses = $dashboardDao->findAllExpensesDashboardByCompany($id_company);
    $response->getBody()->write(json_encode($expenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
