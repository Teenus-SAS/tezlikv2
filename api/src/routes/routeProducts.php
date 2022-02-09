<?php

use tezlikv2\dao\ProductsDao;

$productsDao = new ProductsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/products', function (Request $request, Response $response, $args) use ($productsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $products = $productsDao->findAllProductsByCompany($id_company);
    $response->getBody()->write(json_encode($products, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProducts', function (Request $request, Response $response, $args) use ($productsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataproducts = $request->getParsedBody();
    //$files = $request->getUploadedFiles();
    /* Falta la programacion para la carga de la imagen */
    if (empty($dataproducts['referenceProduct']) || empty($dataproducts['product']) || empty($dataproducts['profitability']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {

        $products = $productsDao->InsertUpdateProductByCompany($dataproducts, $id_company);

        if ($products == 1)
            $resp = array('success' => true, 'message' => 'Producto creado correctamente');
        else if ($products == 2)
            $resp = array('success' => true, 'message' => 'Producto actualizado correctamente');
        else
            $resp = $products;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteProduct/{id}', function (Request $request, Response $response, $args) use ($productsDao) {
    $product = $productsDao->deleteProduct($args['id']);
    if ($product == null)
        $resp = array('success' => true, 'message' => 'Producto eliminado correctamente');

    if ($product != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el producto, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/productshasmaterials/{id}', function (Request $request, Response $response, $args) use ($productsDao) {
    $productMaterials = $productsDao->productshasmaterials($args['id']);
    $response->getBody()->write(json_encode($productMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/productshasprocess/{id}', function (Request $request, Response $response, $args) use ($productsDao) {
    $productProcess = $productsDao->productshasprocess($args['id']);
    $response->getBody()->write(json_encode($productProcess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/externalservices/{id}', function (Request $request, Response $response, $args) use ($productsDao) {
    $externalServices = $productsDao->externalservices($args['id']);
    $response->getBody()->write(json_encode($externalServices, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
