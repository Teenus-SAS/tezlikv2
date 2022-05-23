<?php

use tezlikv2\dao\CompaniesLicenseDao;

$companiesLicenseDao = new CompaniesLicenseDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



//DATOS BÁSICOS EMPRESA LICENCIA Y DIAS QUE QUEDAN POR LICENCIA

$app->post('/licenseCompany', function (Request $request, Response $response, $args) use ($companiesLicenseDao) {
    $dataLicenseCompany = $request->getParsedBody();

    //OBTENER DATOS EMPRESA/LICENCIA
    $dataCompany = $companiesLicenseDao->findCompanyLicense();

    //OBTENER DIAS RESTANTES LICENCIA
    // $daysLic = $companiesLicenseDao->findLicenseDays(id_company);

    $resp = 0;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
