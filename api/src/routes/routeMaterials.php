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

$app->post('/addmaterials', function (Request $request, Response $response, $args) use ($materialsDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataMaterials = $request->getParsedBody();
   
    if (empty($dataMaterials['referenceProduct']) || empty($dataMaterials['product']) || empty($dataMaterials['profitability']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {

        $materials = $materialsDao->InsertUpdateMaterialsByCompany($dataMaterials, $id_company);

        if ($materials == 1)
            $resp = array('success' => true, 'message' => 'Materia Prima creada correctamente');
        else if ($materials == 2)
            $resp = array('success' => true, 'message' => 'Materia Prima actualizada correctamente');
        else
            $resp = $materials;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
