<?php

use tezlikv2\dao\ExternalServicesDao;

$externalServicesDao = new ExternalServicesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/externalservices/{id_product}', function (Request $request, Response $response, $args) use ($externalServicesDao) {
    $externalServices = $externalServicesDao->externalServices($args['id_product']);
    $response->getBody()->write(json_encode($externalServices, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addExternalService', function (Request $request, Response $response, $args) use ($externalServicesDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataExternalService = $request->getParsedBody();

    if (empty($dataExternalService['service']) || empty($dataExternalService['cost']) || empty($dataExternalService['idProduct']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $externalServices = $externalServicesDao->insertExternalServicesByCompany($dataExternalService, $id_company);

        if ($externalServices == 1)
            $resp = array('success' => true, 'message' => 'Servicio externo ingresado correctamente');
        else
            $resp = $externalServices;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateExternalService', function (Request $request, Response $response, $args) use ($externalServicesDao) {
    $dataExternalService = $request->getParsedBody();

    if (empty($dataExternalService['service']) || empty($dataExternalService['cost']) || empty($dataExternalService['idProduct']))
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $externalServices = $externalServicesDao->updateExternalServices($dataExternalService);

        if ($externalServices == 2)
            $resp = array('success' => true, 'message' => 'Servicio externo actualizado correctamente');
        else
            $resp = $externalServices;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteExternalService/{id_service}', function (Request $request, Response $response, $args) use ($externalServicesDao) {
    $externalServices = $externalServicesDao->deleteExternalService($args['id_service']);
    if ($externalServices == null)
        $resp = array('success' => true, 'message' => 'Servicio externo eliminado correctamente');
    if ($externalServices != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el servicio externo, existe información asociada a él');
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
