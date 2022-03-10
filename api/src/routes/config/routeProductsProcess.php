<?php

use tezlikv2\dao\ProductsProcessDao;

$productsProcessDao = new ProductsProcessDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// PRODUCTOS PROCESOS
$app->get('/productsProcess/{refProduct}', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    $productProcess = $productsProcessDao->productsprocess($args['refProduct']);
    $response->getBody()->write(json_encode($productProcess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['refProduct'] || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        //$productProcess = $productsProcessDao->insertproductsprocessByCompany($dataProductProcess);
        $productProcess = $productsProcessDao->insertProductsProcessByCompany($dataProductProcess, $id_company);

        if ($productProcess == 1)
            $resp = array('success' => true, 'message' => 'Proceso asignado correctamente');
        else
            $resp = $productProcess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['idProduct'] || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productProcess = $productsProcessDao->updateProductsProcess($dataProductProcess);

        if ($productProcess == 2)
            $resp = array('success' => true, 'message' => 'Proceso actualizado correctamente');
        else
            $resp = $productProcess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteProductProcess/{id_product_process}', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    $product = $productsProcessDao->deleteProductProcess($args['id_product_process']);
    if ($product == null)
        $resp = array('success' => true, 'message' => 'Proceso eliminado correctamente');

    if ($product != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el proceso asignado, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
