<?php

use LDAP\Result;
use tezlikv2\dao\PayrollDao;

$payrollDao = new PayrollDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/payroll', function (Request $request, Response $response, $args) use ($payrollDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $payroll = $payrollDao->findAllPayrollByCompany($id_company);
    $response->getBody()->write(json_encode($payroll, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addPayroll', function (Request $request, Response $response) use ($payrollDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataPayroll = $request->getParsedBody();

    if (
        // || empty($dataPayroll['minuteValue']) empty($dataPayroll['endowment']) || 
        empty($dataPayroll['employee']) || empty($dataPayroll['basicSalary']) || empty($dataPayroll['idProcess']) ||
        empty($dataPayroll['workingDaysMonth']) || empty($dataPayroll['workingHoursDay']) ||
        empty($dataPayroll['typeFactor'])
    )

        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $payroll = $payrollDao->insertPayrollByCompany($dataPayroll, $id_company);

        if ($payroll == 1)
            $resp = array('success' => true, 'message' => 'Nomina creada correctamente');
        else
            $resp = $payroll;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updatePayroll', function (Request $request, Response $response, $args) use ($payrollDao) {
    $dataPayroll = $request->getParsedBody();

    if (
        empty($dataPayroll['employee']) || empty($dataPayroll['basicSalary']) ||
        empty($dataPayroll['workingDaysMonth']) || empty($dataPayroll['workingHoursDay'])
        || empty($dataPayroll['bonification']) || empty($dataPayroll['typeFactor'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $payroll = $payrollDao->updatePayroll($dataPayroll);

        if ($payroll == 2)
            $resp = array('success' => true, 'message' => 'Nomina actualizada correctamente');
        else
            $resp = $payroll;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deletePayroll/{id_payroll}', function (Request $request, Response $response, $args) use ($payrollDao) {
    $payroll = $payrollDao->deletePayroll($args['id_payroll']);

    if ($payroll == null)
        $resp = array('success' => true, 'message' => 'Nomina eliminada correctamente');
    if ($payroll != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar la nomina, existe información asociada a ella');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
