<?php

use tezlikv2\dao\ExpensesDistributionDao;

$expensesDistributionDao = new ExpensesDistributionDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Message;

/* Consulta todos */

$app->get('/expensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao) {
    $expensesDistribution = $expensesDistributionDao->findAllExpensesDistributionByCompany();
    $response->getBody()->write(json_encode($expensesDistribution, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addExpensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExpensesDistribution = $request->getParsedBody();

    if (empty($dataExpensesDistribution['idProduct']) || empty($dataExpensesDistribution['unitsSold']) || empty($dataExpensesDistribution['turnover']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expensesDistribution = $expensesDistributionDao->insertExpensesDistributionByCompany($dataExpensesDistribution, $id_company);

        if ($expensesDistribution == 1)
            $resp = array('succes' => true, 'message' => 'Distribución de gasto asignado correctamente');
        else
            $resp = $expensesDistribution;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateExpensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao) {
    $dataExpensesDistribution = $request->getParsedBody();

    if (empty($dataExpensesDistribution['idProduct']) || empty($dataExpensesDistribution['unitsSold']) || empty($dataExpensesDistribution['turnover']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expensesDistribution = $expensesDistributionDao->updateExpensesDistribution($dataExpensesDistribution);

        if ($expensesDistribution == 1)
            $resp = array('succes' => true, 'message' => 'Distribución de gasto actualizada correctamente');
        else
            $resp = $expensesDistribution;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteExpensesDistribution/{id_expenses}', function (Request $request, Response $response, $args) use ($expensesDistributionDao) {
    $expensesDistribution = $expensesDistributionDao->deleteExpensesDistribution($args['id_expenses']);

    if ($expensesDistribution == null)
        $resp = array('success' => true, 'message' => 'Distribucion de gasto eliminado correctamente');
    if ($expensesDistribution != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el gasto, existe información asociada a él');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
