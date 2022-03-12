<?php

use tezlikv2\dao\FactoryLoadDao;

$factoryloadDao = new FactoryLoadDao();

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

$app->post('/addFactoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataFactoryLoad = $request->getParsedBody();

    if (
        empty($dataFactoryLoad['idMachine']) || empty($dataFactoryLoad['descriptionFactoryLoad']) || empty($dataFactoryLoad['cost'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $factoryLoad = $factoryloadDao->insertFactoryLoadByCompany($dataFactoryLoad, $id_company);

        if ($factoryLoad == 1)
            $resp = array('success' => true, 'message' => 'Carga fabril creada correctamente');
        else
            $resp = $factoryLoad;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateFactoryLoad', function (Request $request, Response $response, $args) use ($factoryloadDao) {
    $dataFactoryLoad = $request->getParsedBody();

    if (
        empty($dataFactoryLoad['idMachine']) || empty($dataFactoryLoad['descriptionFactoryLoad']) || empty($dataFactoryLoad['cost'])
    )
        $resp = array('error' => true, 'message' => 'No hubo cambio alguno');
    else {
        $factoryLoad = $factoryloadDao->updateFactoryLoad($dataFactoryLoad);

        if ($factoryLoad == 2)
            $resp = array('success' => true, 'message' => 'Carga fabril actualizada correctamente');
        else
            $resp = $factoryLoad;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteFactoryLoad/{id_manufacturing_load}', function (Request $request, Response $response, $args) use ($factoryloadDao) {
    $factoryLoad = $factoryloadDao->deleteFactoryLoad($args['id_manufacturing_load']);

    if ($factoryLoad == null)
        $resp = array('success' => true, 'message' => 'Carga fabril eliminada correctamente');
    if ($factoryLoad != null)
        $resp = array('error' => true, 'message' => 'No se pudo eliminar la carga fabril, existe información asociada a ella');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
