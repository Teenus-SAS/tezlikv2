<?php

use tezlikv2\dao\ExpensesDistributionDao;
use tezlikv2\dao\TotalExpenseDao;
use tezlikv2\dao\AssignableExpenseDao;

$expensesDistributionDao = new ExpensesDistributionDao();
$totalExpenseDao = new TotalExpenseDao();
$assignableExpenseDao = new AssignableExpenseDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/expensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao) {
    $expensesDistribution = $expensesDistributionDao->findAllExpensesDistributionByCompany();
    $response->getBody()->write(json_encode($expensesDistribution, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/expenseTotal', function (Request $request, Response $response, $args) use ($totalExpenseDao) {
    $totalExpense = $totalExpenseDao->findTotalExpenseByCompany();
    $response->getBody()->write(json_encode($totalExpense, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addExpensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao, $assignableExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExpensesDistribution = $request->getParsedBody();

    if (empty($dataExpensesDistribution['selectNameProduct']) || empty($dataExpensesDistribution['unitsSold']) || empty($dataExpensesDistribution['turnover']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expensesDistribution = $expensesDistributionDao->insertExpensesDistributionByCompany($dataExpensesDistribution, $id_company);
        // Calcular gasto asignable
        $assignableExpense = $assignableExpenseDao->calcAssignableExpense($id_company);

        if ($expensesDistribution == null && $assignableExpense == null)
            $resp = array('success' => true, 'message' => 'Distribución de gasto asignado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras almacenaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateExpensesDistribution', function (Request $request, Response $response, $args) use ($expensesDistributionDao, $assignableExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExpensesDistribution = $request->getParsedBody();

    if (empty($dataExpensesDistribution['selectNameProduct']) || empty($dataExpensesDistribution['unitsSold']) || empty($dataExpensesDistribution['turnover']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expensesDistribution = $expensesDistributionDao->updateExpensesDistribution($dataExpensesDistribution);
        // Calcular gasto asignable
        $assignableExpense = $assignableExpenseDao->calcAssignableExpense($id_company);

        if ($expensesDistribution == null && $assignableExpense == null)
            $resp = array('success' => true, 'message' => 'Distribución de gasto actualizada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteExpensesDistribution/{id_expenses_distribution}', function (Request $request, Response $response, $args) use ($expensesDistributionDao, $assignableExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $expensesDistribution = $expensesDistributionDao->deleteExpensesDistribution($args['id_expenses_distribution']);
    $assignableExpense = $assignableExpenseDao->calcAssignableExpense($id_company);

    if ($expensesDistribution == null && $assignableExpense == null)
        $resp = array('success' => true, 'message' => 'Distribucion de gasto eliminado correctamente');
    else
        $resp = array('error' => true, 'message' => 'No es posible eliminar el gasto, existe información asociada a él');
    
        $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
