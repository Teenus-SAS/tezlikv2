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

/* Consultar productos importados */
$app->post('/productsDataValidation', function (Request $request, Response $response, $args) use ($productsDao) {
    $dataProduct = $request->getParsedBody();

    if (isset($dataProduct)) {
        session_start();
        $id_company = $_SESSION['id_company'];

        $insert = 0;
        $update = 0;

        $products = $dataProduct['importProducts'];

        for ($i = 0; $i < sizeof($products); $i++) {

            $reference = $products[$i]['referenceProduct'];
            $product = $products[$i]['product'];
            $profitability = $products[$i]['profitability'];
            $commisionSale = $products[$i]['commissionSale'];

            if (empty($reference) || empty($product) || empty($profitability) && is_numeric($profitability) || empty($commisionSale) && is_numeric($commisionSale))
                $dataImportProduct = array('error' => true, 'message' => 'Ingrese todos los datos');
            else {
                $findProduct = $productsDao->findProduct($products[$i], $id_company);
                if (!$findProduct) $insert = $insert + 1;
                else $update = $update + 1;
                $dataImportProduct['insert'] = $insert;
                $dataImportProduct['update'] = $update;
            }
        }
    } else
        $dataImportProduct = array('error' => true, 'message' => 'El archivo se encuentra vacio. Intente nuevamente');

    $response->getBody()->write(json_encode($dataImportProduct, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProducts', function (Request $request, Response $response, $args) use ($productsDao, $productsCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProduct = $request->getParsedBody();

    /* Inserta datos */
    $dataProducts = sizeof($dataProduct);

    if ($dataProducts > 1) {
        $products = $productsDao->insertProductByCompany($dataProduct, $id_company);
        $lastProductId = $productsDao->lastInsertedProductId($id_company);
        $imgProducts = $productsDao->imageProduct($lastProductId['id_product'], $id_company);
        $productsCost = $productsCostDao->insertProductsCostByCompany($dataProduct, $id_company);

        if ($products == null && $imgProducts == null && $productsCost == null)
            $resp = array('success' => true, 'message' => 'Producto creado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrió un error mientras ingresaba la información. Intente nuevamente');
    } else {
        $products = $dataProduct['importProducts'];

        for ($i = 0; $i < sizeof($products); $i++) {

            $product = $productsDao->findProduct($products[$i], $id_company);

            if (!$product) {
                $resolution = $productsDao->insertProductByCompany($products[$i], $id_company);
                $resolution = $productsCostDao->insertProductsCostByCompany($products[$i], $id_company);
            } else {
                $products[$i]['idProduct'] = $product['id_product'];
                $resolution = $productsDao->updateProductByCompany($products[$i]);
                $resolution = $productsCostDao->updateProductsCostByCompany($products[$i]);
            }
        }
        if ($resolution == null)
            $resp = array('success' => true, 'message' => 'Productos importados correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrió un error mientras importaba los datos. Intente nuevamente');
    }



    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProducts', function (Request $request, Response $response, $args) use ($productsDao, $productsCostDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $dataProduct = $request->getParsedBody();
    //$imgProduct = $request->getUploadedFiles();

    if (
        empty($dataProduct['referenceProduct']) || empty($dataProduct['product']) ||
        empty($dataProduct['profitability']) || empty($dataProduct['commissionSale'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos a actualizar');
    else {
        // Actualizar Datos, Imagen y Calcular Precio del producto
        $products = $productsDao->updateProductByCompany($dataProduct, $id_company);

        if (sizeof($_FILES) > 0)
            $products = $productsDao->imageProduct($dataProduct['idProduct'], $id_company);

        $products = $productsCostDao->updateProductsCostByCompany($dataProduct);
        $products = $priceProductDao->calcPrice($dataProduct['idProduct']);

        if ($products == null)
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
