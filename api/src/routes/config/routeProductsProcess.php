<?php

use tezlikv2\dao\ProductsProcessDao;
use tezlikv2\dao\CalcProductsCostDao;

$productsProcessDao = new ProductsProcessDao();
$calcProductsCostDao = new CalcProductsCostDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Productos procesos
$app->get('/productsProcess/{idProduct}', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    $productProcess = $productsProcessDao->productsprocess($args['idProduct']);
    $response->getBody()->write(json_encode($productProcess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['idProduct'] || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productProcess = $productsProcessDao->insertProductsProcessByCompany($dataProductProcess, $id_company);

        /* Calcular costo nomina */
        $calcCostPayroll = $calcProductsCostDao->calcCostPayroll($dataProductProcess, $id_company);

        /* Calcular costo indirecto */
        $calcIndirectCost = $calcProductsCostDao->calcCostIndirectCost($dataProductProcess, $id_company);

        if ($productProcess == 1)
            $resp = array('success' => true, 'message' => 'Proceso asignado correctamente');
        else
            $resp = $productProcess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['idProduct'] || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productProcess = $productsProcessDao->updateProductsProcess($dataProductProcess);

        /* Calcular costo nomina */
        $calcCostPayroll = $calcProductsCostDao->calcCostPayroll($dataProductProcess, $id_company);

        /* Calcular costo indirecto */
        $calcIndirectCost = $calcProductsCostDao->calcCostIndirectCost($dataProductProcess, $id_company);


        if ($productProcess == 2)
            $resp = array('success' => true, 'message' => 'Proceso actualizado correctamente');
        else
            $resp = $productProcess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProductProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    $product = $productsProcessDao->deleteProductProcess($dataProductProcess);

    /* Calcular costo nomina */
    $calcCostPayroll = $calcProductsCostDao->calcCostPayroll($dataProductProcess, $id_company);

    /* Calcular costo indirecto */
    $calcIndirectCost = $calcProductsCostDao->calcCostIndirectCost($dataProductProcess, $id_company);


    if ($product == null)
        $resp = array('success' => true, 'message' => 'Proceso eliminado correctamente');

    if ($product != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el proceso asignado, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
