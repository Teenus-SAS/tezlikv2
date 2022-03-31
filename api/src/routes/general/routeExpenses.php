<?php

use tezlikv2\dao\ExpensesDao;
use tezlikv2\dao\TotalExpenseDao;

$expensesDao = new ExpensesDao();
$totalExpenseDao = new TotalExpenseDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/expenses', function (Request $request, Response $response, $args) use ($expensesDao) {
    $expenses = $expensesDao->findAllExpensesByCompany();
    $response->getBody()->write(json_encode($expenses, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addExpenses', function (Request $request, Response $response, $args) use ($expensesDao, $totalExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExpenses = $request->getParsedBody();

    if (empty($dataExpenses['idPuc']) || empty($dataExpenses['expenseValue']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expenses = $expensesDao->insertExpensesByCompany($dataExpenses, $id_company);

        // Calcular total del gasto
        $totalExpense = $totalExpenseDao->insertUpdateTotalExpense($id_company);

        if ($expenses == 1)
            $resp = array('success' => true, 'message' => 'Gasto creado correctamente');
        else
            $resp = $expenses;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateExpenses', function (Request $request, Response $response, $args) use ($expensesDao, $totalExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExpenses = $request->getParsedBody();

    if (empty($dataExpenses['idPuc']) || empty($dataExpenses['expenseValue']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $expenses = $expensesDao->updateExpenses($dataExpenses);

        // Calcular total del gasto
        $totalExpense = $totalExpenseDao->insertUpdateTotalExpense($id_company);

        if ($expenses == 2)
            $resp = array('success' => true, 'message' => 'Gasto actualizado correctamente');
        else
            $resp = $expenses;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteExpenses/{id_expense}', function (Request $request, Response $response, $args) use ($expensesDao, $totalExpenseDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $expenses = $expensesDao->deleteExpenses($args['id_expense']);

    // Calcular total del gasto
    $totalExpense = $totalExpenseDao->insertUpdateTotalExpense($id_company);

    if ($expenses == null)
        $resp = array('success' => true, 'message' => 'Gasto eliminado correctamente');
    if ($expenses != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el gasto, existe información asociada a él');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
