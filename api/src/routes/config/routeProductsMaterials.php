<?php

use tezlikv2\dao\ProductsMaterialsDao;
use tezlikv2\dao\CostMaterialsDao;
use tezlikv2\dao\PriceProductDao;

$productsMaterialsDao = new ProductsMaterialsDao();
$costMaterialsDao = new CostMaterialsDao();
$priceProductDao = new PriceProductDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/productsMaterials/{idProduct}', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];

    $productMaterials = $productsMaterialsDao->productsmaterials($args['idProduct'], $id_company);

    $response->getBody()->write(json_encode($productMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/importProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao) {
    $dataProductMaterial = $request->getParsedBody();

    $insert = 0;
    $update = 0;
    for ($i = 0; $i < sizeof($dataProductMaterial['importProductsMaterials']); $i++) {
        $dataFindProductsMaterials = $productsMaterialsDao->findAExistingProductMaterial($dataProductMaterial['importProductsMaterials'][$i]);
        if (empty($dataFindProductsMaterials['id_product_material'])) {
            $insert = $insert + 1;
        } else
            $update = $update + 1;
    }
    $dataImportProductsMaterials = array($insert, $update);

    $response->getBody()->write(json_encode($dataImportProductsMaterials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['importProductsMaterials'])) {
        if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct']) || empty($dataProductMaterial['quantity']))
            $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
        else {
            $productMaterials = $productsMaterialsDao->insertProductsMaterialsByCompany($dataProductMaterial, $id_company);
            //Metodo calcular precio total materias
            $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial['idProduct'], $id_company);

            // Calcular Precio del producto
            $priceProduct = $priceProductDao->calcPrice($dataProductMaterial['idProduct']);

            if ($productMaterials == null && $costMaterials == null && $priceProduct == null)
                $resp = array('success' => true, 'message' => 'Materia prima asignada correctamente');
            else
                $resp = array('error' => true, 'message' => 'Ocurrio un error mientras asignaba la información. Intente nuevamente');
        }
    } else {
        for ($i = 0; $i < sizeof($dataProductMaterial['importProductsMaterials']); $i++) {
            if (
                empty($dataProductMaterial['importProductsMaterials'][$i]['refRawMaterial']) || empty($dataProductMaterial['importProductsMaterials'][$i]['referenceProduct']) ||
                empty($dataProductMaterial['importProductsMaterials'][$i]['quantity'])
            )
                $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
            else {
                // Insertar o Actualizar 
                $productMaterials = $productsMaterialsDao->insertOrUpdateProductsMaterials($dataProductMaterial['importProductsMaterials'][$i], $id_company);

                // Obtener id de producto y materia prima
                $dataFindProductsMaterials = $productsMaterialsDao->findAExistingProductMaterial($dataProductMaterial['importProductsMaterials'][$i]);

                //Metodo calcular precio total materias
                $costMaterials = $costMaterialsDao->calcCostMaterial($dataFindProductsMaterials['id_product'], $id_company);

                // Calcular Precio del producto
                $priceProduct = $priceProductDao->calcPrice($dataFindProductsMaterials['id_product']);
            }
        }
        if ($productMaterials == null /*&& $costMaterials == null && $priceProduct == null*/)
            $resp = array('success' => true, 'message' => 'Materia prima importada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras importada la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProductsMaterials', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    if (empty($dataProductMaterial['material']) || empty($dataProductMaterial['idProduct'] || empty($dataProductMaterial['quantity'])))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $productMaterials = $productsMaterialsDao->updateProductsMaterials($dataProductMaterial);

        //Metodo calcular precio total materias
        $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial, $id_company);

        // Calcular Precio del producto
        $priceProduct = $priceProductDao->calcPrice($dataProductMaterial['idProduct']);

        if ($productMaterials == null && $costMaterials == null && $priceProduct == null)
            $resp = array('success' => true, 'message' => 'Materia prima actualizada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteProductMaterial', function (Request $request, Response $response, $args) use ($productsMaterialsDao, $costMaterialsDao, $priceProductDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataProductMaterial = $request->getParsedBody();

    $product = $productsMaterialsDao->deleteProductMaterial($dataProductMaterial);

    //Metodo calcular precio total materias
    $costMaterials = $costMaterialsDao->calcCostMaterial($dataProductMaterial['idProduct'], $id_company);

    // Calcular Precio del producto
    $priceProduct = $priceProductDao->calcPrice($dataProductMaterial['idProduct']);

    if ($product == null && $costMaterials == null && $priceProduct == null)
        $resp = array('success' => true, 'message' => 'Materia prima eliminada correctamente');

    else
        $resp = array('error' => true, 'message' => 'No es posible eliminar la materia prima asignada, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
