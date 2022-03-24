<?php

use tezlikv2\dao\ProductsMaterialsDao;
use tezlikv2\dao\CalcProductsCostDao;

$productsMaterialsDao = new ProductsMaterialsDao();
$calcProductsCostDao = new CalcProductsCostDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/productsMaterials/{idProduct}', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    $productMaterials = $productsMaterialsDao->productsmaterials($args['idProduct']);
    $response->getBody()->write(json_encode($productMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->insertProductsMaterialsByCompany($dataProductMaterial, $id_company);
        //Metodo calcular precio total materias
        $productCost = $calcProductsCostDao->calCostMaterialsProduct($dataProductMaterial, $id_company);

        if ($productMaterials == 1)
            $resp = array('success' => true, 'message' => 'Materia prima asignada correctamente');
        else
            $resp = $productMaterials;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->updateProductsMaterials($dataProductMaterial);
        //Metodo calcular precio total materias
        //$productCost = $calcProductsCostDao->costProducts($dataProductMaterial, $id_company);

        if ($productMaterials == 2)
            $resp = array('success' => true, 'message' => 'Materia prima actualizada correctamente');
        else
            $resp = $productMaterials;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProductMaterial', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $calcProductsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    $product = $productsMaterialsDao->deleteProductMaterial($dataProductMaterial);
    //Metodo calcular precio total materias
    //$productCost = $calcProductsCostDao->costProducts($dataProductMaterial, $id_company);

    if ($product == null)
        $resp = array('success' => true, 'message' => 'Materia prima eliminada correctamente');

    if ($product != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar la materia prima asignada, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
