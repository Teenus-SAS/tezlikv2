<?php

use tezlikv2\dao\MaterialsDao;
use tezlikv2\dao\CostMaterialsDao;

$materialsDao = new MaterialsDao();
$costMaterialsDao = new CostMaterialsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/materials', function (Request $request, Response $response, $args) use ($materialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $materials = $materialsDao->findAllMaterialsByCompany($id_company);
    $response->getBody()->write(json_encode($materials, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addMaterials', function (Request $request, Response $response, $args) use ($materialsDao) {
    session_start();
    $dataMaterial = $request->getParsedBody();
    $id_company = $_SESSION['id_company'];

    if (empty($dataMaterial['refRawMaterial']) || empty($dataMaterial['nameRawMaterial']) || empty($dataMaterial['unityRawMaterial']) || empty($dataMaterial['costRawMaterial']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {

        $materials = $materialsDao->insertMaterialsByCompany($dataMaterial, $id_company);

        if ($materials == null)
            $resp = array('success' => true, 'message' => 'Materia Prima creada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras ingresaba la información. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateMaterials', function (Request $request, Response $response, $args) use ($materialsDao, $costMaterialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataMaterial = $request->getParsedBody();

    $materials = $materialsDao->updateMaterialsByCompany($dataMaterial);

    // Calcular precio total materias
    $costMaterials = $costMaterialsDao->calcCostMaterialsByRawMaterial($dataMaterial, $id_company);

    if ($materials == null && $costMaterials == null)
        $resp = array('success' => true, 'message' => 'Materia Prima actualizada correctamente');
    else
        $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteMaterial', function (Request $request, Response $response, $args) use ($materialsDao) {
    /*session_start();
    $id_company = $_SESSION['id_company'];*/
    $dataMaterial = $request->getParsedBody();

    $materials = $materialsDao->deleteMaterial($dataMaterial);
    // Calcular precio total materias
    //$productCost = $calcProductsCostDao->calcCostMaterialsByRawMaterial($dataMaterial, $id_company);

    if ($materials == null)
        $resp = array('success' => true, 'message' => 'Material eliminado correctamente');

    if ($materials != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el material, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
