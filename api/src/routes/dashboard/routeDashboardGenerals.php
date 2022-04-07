<?php

use tezlikv2\dao\DashboardGeneralDao;

$dashboardGeneralDao = new DashboardGeneralDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardExpensesGenerals', function (Request $request, Response $response, $args) use ($dashboardGeneralDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    // Consultar valor por minuto del proceso
    $processMinuteValue = $dashboardGeneralDao->findProcessMinuteValueByCompany($id_company);

    // Consulta valor por minuto de la maquina
    $factoryLoadMinuteValue = $dashboardGeneralDao->findfactoryLoadMinuteValueByCompany($id_company);

    // Consulta valor del gasto
    $expenseValue = $dashboardGeneralDao->findExpensesValueByCompany($id_company);

    $generalExpenses['process_minute_value'] = $processMinuteValue;
    $generalExpenses['factory_load_minute_value'] = $factoryLoadMinuteValue;
    $generalExpenses['expense_value'] = $expenseValue;

    $response->getBody()->write(json_encode($generalExpenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
