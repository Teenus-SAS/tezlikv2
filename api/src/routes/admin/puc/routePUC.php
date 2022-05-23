<?php

use tezlikv2\dao\PucDao;

$pucDao = new PucDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


//CONSULTA PUC GENERAL
$app->post('/puc', function (Request $request, Response $response, $args) use ($pucDao) {

    $resp = $pucDao->findAllCountsPUC();

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//ACTUALIZACION PUC
//AGREGAR ID
$app->post('/updatePuc/{id_puc}', function (Request $request, Response $response, $args) use ($pucDao) {
    $dataPuc = $request->getParsedBody();

    // ACTUALIZAR PUC
    // $respPuc = $pucDao->updateCountsPUC(id_puc);
    $resp = 0;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
