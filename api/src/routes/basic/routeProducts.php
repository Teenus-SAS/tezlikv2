<?php

use tezlikv2\dao\ProductsDao;
use tezlikv2\dao\ProductsCostDao;
use tezlikv2\dao\PriceProductDao;

$productsDao = new ProductsDao();
$productsCostDao = new ProductsCostDao();
$priceProductDao = new PriceProductDao();

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

$app->post('/addProducts', function (Request $request, Response $response, $args) use ($productsDao, $productsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProduct = $request->getParsedBody();

    //$files = $request->getUploadedFiles();
    /* Falta la programacion para la carga de la imagen */

    if (
        empty($dataProduct['referenceProduct']) || empty($dataProduct['product']) ||
        empty($dataProduct['profitability']) || empty($dataProduct['commisionSale'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $products = $productsDao->insertProductByCompany($dataProduct, $id_company);
        //Insertar en products_costs
        $productsCost = $productsCostDao->insertProductsCostByCompany($dataProduct, $id_company);

        if ($products == null && $productsCost == null)
            $resp = array('success' => true, 'message' => 'Producto creado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras ingresaba la información. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProducts', function (Request $request, Response $response, $args) use ($productsDao, $productsCostDao, $priceProductDao) {
    $dataProduct = $request->getParsedBody();

    //$files = $request->getUploadedFiles();
    /* Falta la programacion para la carga de la imagen */

    if (
        empty($dataProduct['referenceProduct']) || empty($dataProduct['product']) ||
        empty($dataProduct['profitability']) || empty($dataProduct['commisionSale'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos a actualizar');
    else {
        $products = $productsDao->updateProduct($dataProduct);
        $productsCost = $productsCostDao->updateProductsCost($dataProduct);

        // Calcular Precio del producto
        $priceProduct = $priceProductDao->calcPrice($dataProduct['idProduct']);

        if ($products == null && $productsCost == null && $priceProduct == null)
            $resp = array('success' => true, 'message' => 'Producto actualizado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProduct', function (Request $request, Response $response, $args) use ($productsDao, $productsCostDao) {
    $dataProduct = $request->getParsedBody();

    $productsCost = $productsCostDao->deleteProductsCost($dataProduct);
    $product = $productsDao->deleteProduct($dataProduct);

    if ($product == null && $productsCost == null)
        $resp = array('success' => true, 'message' => 'Producto eliminado correctamente');
    else
        $resp = array('error' => true, 'message' => 'No es posible eliminar el producto, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
