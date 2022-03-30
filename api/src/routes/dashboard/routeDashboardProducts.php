<?php

use tezlikv2\dao\DashboardDao;
use tezlikv2\dao\ProductsProcessDao;
use tezlikv2\dao\ProductsMaterialsDao;

$dashboardDao = new DashboardDao();
$productProcessDao = new ProductsProcessDao();
$productsMaterialsDao = new ProductsMaterialsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardPricesProducts', function (Request $request, Response $response, $args) use ($dashboardDao, $productProcessDao, $productsMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataPrice = $request->getParsedBody();

    // Consultar costo del producto
    $pricesProductsCost = $dashboardDao->findPricesDashboardProductsCost($dataPrice, $id_company);

    // Consultar Distribucion de gasto del producto
    $pricesExpensesDistribution = $dashboardDao->findPricesDashboardExpensesDistribution($dataPrice, $id_company);

    // Consultar Proceso del producto
    $pricesProductProcess = $productProcessDao->productsprocess($dataPrice['idProduct'], $id_company);

    //Consultar Materia prima del Producto
    $pricesProductMaterials = $productsMaterialsDao->productsmaterials($dataPrice['idProduct'], $id_company);

    $prices = $pricesProductsCost + $pricesExpensesDistribution + $pricesProductProcess + $pricesProductMaterials;

    $response->getBody()->write(json_encode($prices, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});