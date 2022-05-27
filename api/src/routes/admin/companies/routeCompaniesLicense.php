<?php

use tezlikv2\dao\CompaniesLicenseDao;

$companiesLicenseDao = new CompaniesLicenseDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


//Nombre de empresa y datos de licencia
$app->get('/licenses', function (Request $request, Response $response, $args) use ($companiesLicenseDao) {
    $resp = $companiesLicenseDao->findCompanyLicenseActive();
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

//Actualizar fechas de licencia y cantidad de usuarios
$app->post('/updateLicense', function (Request $request, Response $response, $args) use ($companiesLicenseDao) {
    $dataLicense = $request->getParsedBody();
    $license = $companiesLicenseDao->updateLicense($dataLicense);

    if ($license == null) {
        $resp = array('success' => true, 'message' => 'Licencia actaulizada correctamente');
    } else {
        $resp = array('error' => true, 'message' => 'Ocurrio un error al actualizar la licencia. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
