<?php

use tezlikv2\dao\CompaniesDao;
use tezlikv2\dao\CompaniesLicenseDao;
use tezlikv2\dao\CompaniesStatusDao;

$companiesDao = new CompaniesDao();
$companiesLicDao = new CompaniesLicenseDao();
$companiesStatusDao = new CompaniesStatusDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//Datos de empresas activas
$app->get('/companies/{stat}', function (Request $request, Response $response, $args) use ($companiesDao) {
    $resp = $companiesDao->findAllCompanies($args['stat']);
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//Nueva Empresa
$app->post('/addNewCompany', function (Request $request, Response $response, $args) use ($companiesDao, $companiesLicDao) {
    $dataCompany = $request->getParsedBody();
    /*Agregar datos a companies */
    $idcompany = $companiesDao->addCompany($dataCompany);
    /*Agregar datos a companies licenses*/
    $company = $companiesLicDao->addLicense($dataCompany, $idcompany['idCompany']);

    if ($company == null) {
        $resp = array('success' => true, 'message' => 'Datos de Empresa agregados correctamente');
    } else {
        $resp = array('error' => true, 'message' => 'Ocurrio un error al actualizar la licencia. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//Actualizar Empresa
$app->post('/updateDataCompany', function (Request $request, Response $response, $args) use ($companiesDao) {
    $dataCompany = $request->getParsedBody();
    $company = $companiesDao->updateCompany($dataCompany);

    if ($company == null) {
        $resp = array('success' => true, 'message' => 'Datos de Empresa actualizados correctamente');
    } else {
        $resp = array('error' => true, 'message' => 'Ocurrio un error al actualizar la licencia. Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//Cambiar estado de empresa
$app->post('/changeStatusCompany', function (Request $request, Response $response, $args) use ($companiesStatusDao) {
    $id_company = $request->getParsedBody();


    // if ($company == null) {
    //     $resp = array('success' => true, 'message' => 'Datos de Empresa actualizados correctamente');
    // } else {
    //     $resp = array('error' => true, 'message' => 'Ocurrio un error al actualizar la licencia. Intente nuevamente');
    // }
    $resp = 0;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
