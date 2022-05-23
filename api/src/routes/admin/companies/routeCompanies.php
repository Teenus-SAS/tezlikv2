<?php

use tezlikv2\dao\CompaniesDao;

$companiesDao = new CompaniesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//DATOS TODAS LAS EMPRESAS


//CANTIDAD DE EMPRESAS

$app->post('/companies', function (Request $request, Response $response, $args) use ($companiesDao) {
    
    //DATOS TODAS LAS EMPRESAS
    $resp = $companiesDao->findAllCompanies();
    
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
