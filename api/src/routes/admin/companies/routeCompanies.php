<?php

use tezlikv2\dao\CompaniesDao;

$companiesDao = new CompaniesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//DATOS TODAS LAS EMPRESAS
//CANTIDAD DE EMPRESAS

$app->post('/quantityCompanies', function (Request $request, Response $response, $args) use ($companiesDao) {
    $dataQuantityCompanies = $request->getParsedBody();

    //DATOS TODAS LAS EMPRESAS
    $activeUsers = $companiesDao->findCompany();
    $resp = $activeUsers;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
