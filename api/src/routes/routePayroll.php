<?php

use tezlikv2\dao\PayrollDao;

$payrollDao = new PayrollDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/payroll', function (Request $request, Response $response, $args) use ($payrollDao) {
    session_start();
    /* $id_company = $_SESSION['empresas_id_empresas']; */
    $payroll = $payrollDao->findAllPayrollByCompany(44);
    $response->getBody()->write(json_encode($payroll, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
