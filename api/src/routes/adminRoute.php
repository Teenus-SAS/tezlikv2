<?php

use tezlikv2\dao\adminDao;

$adminDao = new adminDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//USUARIOS ACTIVOS
//USUARIOS PERMITIDOS POR EMPRESA
//USUARIOS PERMITIDOS POR EMPRESA ACTUALIZAR
//TODAS LAS EMPRESAS
//LICENCIA Y DIAS RESTANTES
//PRODUCTOS GENERALES
//PRODUCTOS POR EMPRESA
//PUC OBTENER
//PUC ACTURLIZAR

//USUARIOS ACTIVOS GENERAL
$app->post('/quantityUsers', function (Request $request, Response $response, $args) use ($adminDao) {
    $dataQuantityUser = $request->getParsedBody();

    $resp = 0;
    //$activeUsers = $adminDao->usersStatus();

    $response->getBody()->write(json_encode($resp, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});


//USUARIOS PERMITIDOS POR EMPRESA
$app->post('/usersAllowedByCompany', function (Request $request, Response $response, $args) use ($adminDao) {
    $dataUsers = $request->getParsedBody();

    $resp = 0;
    // $allowedUsers = $adminDao->usersAllowed();

    $response->getBody()->write(json_encode($resp, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

//USUARIOS PERMITIDOS POR EMPRESA ACTUALIZACIÓN
//AGREGAR ID
$app->post('/updateUsersAllowedByCompany/{id_company}', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataUsers = $request->getParsedBody();

    // $activeUsers = $adminDao->updateUsersAllowed();
    try {
        $resp = 0;
    } catch (\Throwable $th) {
        $resp = 0;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//DATOS TODAS LAS EMPRESAS
//CANTIDAD DE EMPRESAS
$app->post('/quantityCompanies', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataQuantityCompanies = $request->getParsedBody();

    $resp = 0;
    // $activeUsers = $adminDao->findCompany();

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//LICENCIA Y DIAS QUE QUEDAN POR LICENCIA
$app->post('/licenseCompany', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataLicenseCompany = $request->getParsedBody();

    $resp = 0;
    // $activeUsers = $adminDao->findLicenseDays();

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//CANTIDAD DE PRODUCTOS GENERAL
$app->post('/quantityProductsGeneral', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataProducts = $request->getParsedBody();

    $resp = 0;
    // $activeUsers = $adminDao->totalProducts();

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//CANTIDAD DE PRODUCTOS POR EMPRESA
$app->post('/quantityProducts', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataProducts = $request->getParsedBody();

    $resp = 0;
    // $activeUsers = $adminDao->totalProductsByCompany();


    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//CONSULTA PUC GENERAL
$app->post('/puc', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataPuc = $request->getParsedBody();

    $resp = 0;
    // $activeUsers = $adminDao->findAllCountsPUC();

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//ACTUALIZACION PUC
//AGREGAR ID
$app->post('/updatePuc/{id_puc}', function (Request $request, Response $response, $args) use ($licenseCompanyDao) {
    $dataPuc = $request->getParsedBody();

    // $activeUsers = $adminDao->updateCountsPUC();

    try {
        $resp = 0;
    } catch (\Throwable $th) {
        $resp = 0;
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
