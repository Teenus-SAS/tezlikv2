<?php

use tezlikv2\dao\CompaniesLicenseDao;

$companiesLicenseDao = new CompaniesLicenseDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



//DATOS BÁSICOS EMPRESA LICENCIA Y DIAS QUE QUEDAN POR LICENCIA

$app->post('/licenseCompany', function (Request $request, Response $response, $args) use ($companiesLicenseDao) {
    $dataLicenseCompany = $request->getParsedBody();

    //Obtener datos empresas/licencias activas 
    $dataCompany = $companiesLicenseDao->findCompanyLicenseActive();

    //Obtener días restantes licencias empresas activas
    // $daysLic = $companiesLicenseDao->findLicenseDays(id_company);

    $resp = 0;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
