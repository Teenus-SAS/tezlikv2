<?php

use tezlikv2\dao\FactoryLoadDao;
use tezlikv2\dao\IndirectCostDao;

$factoryloadDao = new FactoryLoadDao();
$indirectCostDao = new IndirectCostDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/factoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $machines = $factoryloadDao->findAllFactoryLoadByCompany($id_company);
    $response->getBody()->write(json_encode($machines, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addFactoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao, $indirectCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataFactoryLoad = $request->getParsedBody();

    if (
        empty($dataFactoryLoad['idMachine']) || empty($dataFactoryLoad['descriptionFactoryLoad']) || empty($dataFactoryLoad['costFactory'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $factoryLoad = $factoryloadDao->insertFactoryLoadByCompany($dataFactoryLoad, $id_company);

        // Calcular costo indirecto
        $indirectCost = $indirectCostDao->calcCostIndirectCostByFactoryLoad($dataFactoryLoad, $id_company);

        if ($factoryLoad == null && $indirectCost == null)
            $resp = array('success' => true, 'message' => 'Carga fabril creada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras ingresaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateFactoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao, $indirectCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataFactoryLoad = $request->getParsedBody();

    if (
        empty($dataFactoryLoad['idMachine']) || empty($dataFactoryLoad['descriptionFactoryLoad']) || empty($dataFactoryLoad['costFactory'])
    )
        $resp = array('error' => true, 'message' => 'No hubo cambio alguno');
    else {
        $factoryLoad = $factoryloadDao->updateFactoryLoad($dataFactoryLoad);

        // Calcular costo indirecto
        $indirectCost = $indirectCostDao->calcCostIndirectCostByFactoryLoad($dataFactoryLoad, $id_company);

        if ($factoryLoad == null && $indirectCost == null)
            $resp = array('success' => true, 'message' => 'Carga fabril actualizada correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error mientras actualizaba la información. Intente nuevamente');
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/deleteFactoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao, $indirectCostDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataFactoryLoad = $request->getParsedBody();

    $factoryLoad = $factoryloadDao->deleteFactoryLoad($dataFactoryLoad);

    // Calcular costo indirecto
    $indirectCost = $indirectCostDao->calcCostIndirectCostByFactoryLoad($dataFactoryLoad, $id_company);


    if ($factoryLoad == null && $indirectCost == null)
        $resp = array('success' => true, 'message' => 'Carga fabril eliminada correctamente');
    else
        $resp = array('error' => true, 'message' => 'No se pudo eliminar la carga fabril, existe información asociada a ella');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
