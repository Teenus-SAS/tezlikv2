<?php

use tezlikv2\dao\DashboardDao;

$dashboardDao = new DashboardDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardExpensesGenerals', function (Request $request, Response $response, $args) use ($dashboardDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $generalExpenses = $dashboardDao->findAllPricesDashboardGeneralsByCompany($id_company);

    $response->getBody()->write(json_encode($generalExpenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
