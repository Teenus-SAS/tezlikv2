<?php

use tezlikv2\dao\ProductsMaterialsDao;

$productsMaterialsDao = new ProductsMaterialsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/productsmaterials/{id_product}', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    $productMaterials = $productsMaterialsDao->productsmaterials($args['id_product']);
    $response->getBody()->write(json_encode($productMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addproductsmaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    session_start();
    $dataProductMaterial = $request->getParsedBody();
    $id_company = $_SESSION['id_company'];

    if (empty($dataProductMaterial['idMaterial']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        //$productMaterials = $productsMaterialsDao->insertProductsMaterialsByCompany($dataProductMaterial);
        $productMaterials = $productsMaterialsDao->insertProductsMaterialsByCompany($dataProductMaterial, $id_company);

        if ($productMaterials == 1)
            $resp = array('success' => true, 'message' => 'Materia prima asignada correctamente');
        else
            $resp = $productMaterials;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateproductsmaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['idMaterial']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->updateProductsMaterials($dataProductMaterial);

        if ($productMaterials == 2)
            $resp = array('success' => true, 'message' => 'Materia prima actualizada correctamente');
        else
            $resp = $productMaterials;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteproductmaterial/{id_product_material}', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    $product = $productsMaterialsDao->deleteProductMaterial($args['id_product_material']);
    if ($product == null)
        $resp = array('success' => true, 'message' => 'Materia prima eliminada correctamente');

    if ($product != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar la materia prima asignada, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
