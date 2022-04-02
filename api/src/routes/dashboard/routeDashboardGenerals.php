<?php

use tezlikv2\dao\DashboardGeneralDao;

$dashboardGeneralDao = new DashboardGeneralDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardExpensesGenerals', function (Request $request, Response $response, $args) use ($dashboardGeneralDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $generalExpenses = $dashboardGeneralDao->findAllPricesDashboardGeneralsByCompany($id_company);

    $response->getBody()->write(json_encode($generalExpenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
