<?php

use tezlikv2\dao\ProcessDao;

$processDao = new ProcessDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/process', function (Request $request, Response $response, $args) use ($processDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $process = $processDao->findAllProcessByCompany($id_company);
    $response->getBody()->write(json_encode($process, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addProcess', function (Request $request, Response $response, $args) use ($processDao) {
    session_start();
    $dataProcess = $request->getParsedBody();
    $id_company = $_SESSION['id_company'];

    if (empty($dataProcess['process']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $process = $processDao->insertProcessByCompany($dataProcess, $id_company);

        if ($process == 1)
            $resp = array('success' => true, 'message' => 'Proceso creado correctamente');
        else
            $resp = $process;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateProcess', function (Request $request, Response $response, $args) use ($processDao) {
    session_start();
    $dataProcess = $request->getParsedBody();

    if (empty($dataProcess['process']))
        $resp = array('error' => true, 'message' => 'No hubo cambio alguno');
    else {
        $process = $processDao->updateProcess($dataProcess);

        if ($process == 2)
            $resp = array('success' => true, 'message' => 'Proceso actualizado correctamente');
        else
            $resp = $process;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteProcess/{id_process}', function (Request $request, Response $response, $args) use ($processDao) {
    $process = $processDao->deleteProcess($args['id_process']);

    if ($process == null)
        $resp = array('success' => true, 'message' => 'Proceso eliminado correctamente');

    if ($process != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el proceso, existe información asociada a él');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
