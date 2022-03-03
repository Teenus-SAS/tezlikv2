<?php

use tezlikv2\dao\MaterialsDao;

$materialsDao = new MaterialsDao();

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

        if ($materials == 1)
            $resp = array('success' => true, 'message' => 'Materia Prima creada correctamente');
        else
            $resp = $materials;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateMaterials', function (Request $request, Response $response, $args) use ($materialsDao) {
    session_start();
    $dataMaterial = $request->getParsedBody();

    $materials = $materialsDao->updateMaterial($dataMaterial);

    if ($materials == 2)
        $resp = array('success' => true, 'message' => 'Materia Prima actualizada correctamente');
    else
        $resp = $materials;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteMaterial/{id_material}', function (Request $request, Response $response, $args) use ($materialsDao) {
    $materials = $materialsDao->deleteMaterial($args['id_material']);

    if ($materials == null)
        $resp = array('success' => true, 'message' => 'Material eliminado correctamente');

    if ($materials != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el material, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
