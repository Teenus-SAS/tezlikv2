<?php

use tezlikv2\dao\MachinesDao;

$machinesDao = new MachinesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/machines', function (Request $request, Response $response, $args) use ($machinesDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $machines = $machinesDao->findAllMachinesByCompany($id_company);
    $response->getBody()->write(json_encode($machines, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addMachines', function (Request $request, Response $response, $args) use ($machinesDao) {
    session_start();
    $datamachines = $request->getParsedBody();
    $id_company = $datamachines['id_company'];

    if (empty($datamachines['machine']) || empty($datamachines['cost']) || empty($datamachines['years_depreciation']) || empty($datamachines['minute_depreciation']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {

        $machines = $machinesDao->insertMachinesByCompany($datamachines, $id_company);

        if ($machines == 1)
            $resp = array('success' => true, 'message' => 'Maquina creada correctamente');
        else
            $resp = $machines;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateMachines', function (Request $request, Response $response, $args) use ($machinesDao) {
    session_start();
    $datamachines = $request->getParsedBody();

    if (empty($datamachines['machine']) || empty($datamachines['cost']) || empty($datamachines['years_depreciation']) || empty($datamachines['minute_depreciation']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos a actualizar');
    else {

        $machines = $machinesDao->updateMachinesByCompany($datamachines);

        if ($machines == 2)
            $resp = array('success' => true, 'message' => 'Maquina actualizada correctamente');
        else
            $resp = $machines;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteMachine/{id_machine}', function (Request $request, Response $response, $args) use ($machinesDao) {
    $machines = $machinesDao->deleteMachine($args['id_machine']);

    if ($machines == null)
        $resp = array('success' => true, 'message' => 'Maquina eliminada correctamente');
    if ($machines != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar la maquina, existe información asociada a él');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
