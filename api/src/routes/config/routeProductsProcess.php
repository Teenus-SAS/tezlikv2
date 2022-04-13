<?php

use tezlikv2\dao\ProductsProcessDao;
use tezlikv2\dao\CostWorkforceDao;
use tezlikv2\dao\IndirectCostDao;
use tezlikv2\Dao\PriceProductDao;

$productsProcessDao = new ProductsProcessDao();
$costWorkforceDao = new CostWorkforceDao();
$indirectCostDao = new IndirectCostDao();
$priceProductDao = new PriceProductDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Productos procesos
$app->get('/productsProcess/{idProduct}', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $productProcess = $productsProcessDao->productsprocess($args['idProduct'], $id_company);
    $response->getBody()->write(json_encode($productProcess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

// Consultar productos procesos importados
$app->post('/importProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao) {
    $dataProductProcess = $request->getParsedBody();

    $insert = 0;
    $update = 0;
    for ($i = 0; $i < sizeof($dataProductProcess['importProductsProcess']); $i++) {
        $dataFindProductProcess = $productsProcessDao->findAExistingProductProcess($dataProductProcess['importProductsProcess'][$i]);

        if (empty($dataFindProductProcess['id_product_process'])) {
            $insert = $insert + 1;
        } else
            $update = $update + 1;
    }
    $dataImportProductProcess = array($insert, $update);

    $response->getBody()->write(json_encode($dataImportProductProcess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $costWorkforceDao, $indirectCostDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['importProductsProcess'])) {

        if (empty($dataProductProcess['idProduct']) || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime']))
            $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
        else {
            $productProcess = $productsProcessDao->insertProductsProcessByCompany($dataProductProcess, $id_company);

            /* Calcular costo nomina */
            $costPayroll = $costWorkforceDao->calcCostPayroll($dataProductProcess, $id_company);

            /* Calcular costo indirecto */
            $indirectCost = $indirectCostDao->calcCostIndirectCost($dataProductProcess, $id_company);

            // Calcular Precio del producto
            $priceProduct = $priceProductDao->calcPrice($dataProductProcess['idProduct']);

            if (
                $productProcess == null && $costPayroll == null &&
                $indirectCost == null && $priceProduct == null
            )
                $resp = array('success' => true, 'message' => 'Proceso asignado correctamente');
            else
                $resp = array('error' => true, 'message' => 'Ocurrio un error mientras asignaba la información. Intente nuevamente');
        }
    } else {
        for ($i = 0; $i < sizeof($dataProductProcess['importProductsProcess']); $i++) {
            if (empty($dataProductProcess['referenceProduct']) || empty($dataProductProcess['process']) || empty($dataProductProcess['referenceMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime']))
                $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
            else {
            }
        }
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $costWorkforceDao, $indirectCostDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    if (empty($dataProductProcess['idProduct'] || empty($dataProductProcess['idProcess']) || empty($dataProductProcess['idMachine']) || empty($dataProductProcess['enlistmentTime']) || empty($dataProductProcess['operationTime'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productProcess = $productsProcessDao->updateProductsProcess($dataProductProcess);

        /* Calcular costo nomina */
        $costPayroll = $costWorkforceDao->calcCostPayroll($dataProductProcess, $id_company);

        /* Calcular costo indirecto */
        $indirectCost = $indirectCostDao->calcCostIndirectCost($dataProductProcess, $id_company);

        // Calcular Precio del producto
        $priceProduct = $priceProductDao->calcPrice($dataProductProcess['idProduct']);

        if (
            $productProcess == null && $costPayroll == null &&
            $indirectCost == null && $priceProduct == null
        )
            $resp = array('success' => true, 'message' => 'Proceso actualizado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProductProcess', function (Request $request, Response $response, $args) use ($productsProcessDao, $costWorkforceDao, $indirectCostDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductProcess = $request->getParsedBody();

    $product = $productsProcessDao->deleteProductProcess($dataProductProcess);

    /* Calcular costo nomina */
    $costPayroll = $costWorkforceDao->calcCostPayroll($dataProductProcess, $id_company);

    /* Calcular costo indirecto */
    $indirectCost = $indirectCostDao->calcCostIndirectCost($dataProductProcess, $id_company);

    // Calcular Precio del producto
    $priceProduct = $priceProductDao->calcPrice($dataProductProcess['idProduct']);

    if (
        $product == null && $costPayroll == null &&
        $indirectCost == null && $priceProduct == null
    )
        $resp = array('success' => true, 'message' => 'Proceso eliminado correctamente');
    else
        $resp = array('error' => true, 'message' => 'No es posible eliminar el proceso asignado, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
