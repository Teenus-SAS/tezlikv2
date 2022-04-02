<?php

use tezlikv2\dao\ProductsMaterialsDao;
use tezlikv2\dao\CostMaterialsDao;

$productsMaterialsDao = new ProductsMaterialsDao();
$costMaterialsDao = new CostMaterialsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/productsMaterials/{idProduct}', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $productMaterials = $productsMaterialsDao->productsmaterials($args['idProduct'], $id_company);

    $response->getBody()->write(json_encode($productMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->insertProductsMaterialsByCompany($dataProductMaterial, $id_company);
        //Metodo calcular precio total materias
        $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial, $id_company);

        if ($productMaterials == null && $costMaterials == null)
            $resp = array('success' => true, 'message' => 'Materia prima asignada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras asignaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->updateProductsMaterials($dataProductMaterial);
        //Metodo calcular precio total materias
        $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial, $id_company);

        if ($productMaterials == null && $costMaterials == null)
            $resp = array('success' => true, 'message' => 'Materia prima actualizada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProductMaterial', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    $product = $productsMaterialsDao->deleteProductMaterial($dataProductMaterial);
    //Metodo calcular precio total materias
    $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial, $id_company);

    if ($product == null && $costMaterials == null)
        $resp = array('success' => true, 'message' => 'Materia prima eliminada correctamente');

    else
        $resp = array('error' => true, 'message' => 'No es posible eliminar la materia prima asignada, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
