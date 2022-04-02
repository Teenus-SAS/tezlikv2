<?php

use tezlikv2\dao\DashboardProductsDao;

$dashboardProductsDao = new DashboardProductsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/dashboardPricesProducts', function (Request $request, Response $response, $args) use ($dashboardProductsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataPrice = $request->getParsedBody();

    // Consultar analisis de costos por producto
    $costAnalysisProducts = $dashboardProductsDao->findCostAnalysisByProduct($dataPrice, $id_company);

    // Consultar Proceso del producto 
    $totalTimeProcess = $dashboardProductsDao->findProductProcessByProduct($dataPrice, $id_company);

    // Consultar Costo Mano de obra por producto
    $costWorkforce = $dashboardProductsDao->findCostWorkforceByProduct($dataPrice, $id_company);

    // Consultar Costo Materia prima por producto
    $costRawMaterials = $dashboardProductsDao->findCostRawMaterialsByProduct($dataPrice, $id_company);

    // Consultar Distribucion de gasto del producto $pricesExpensesDistribution = $dashboardProductsDao->findPricesDashboardExpensesDistribution($dataPrice, $id_company);

    $prices = $costAnalysisProducts + $totalTimeProcess + $costWorkforce + $costRawMaterials;

    $response->getBody()->write(json_encode($prices, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
