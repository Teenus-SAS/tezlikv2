<?php

use tezlikv2\dao\CompanyDao;

$companyDao = new CompanyDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/company', function (Request $request, Response $response, $args) use ($companyDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $company = $companyDao->findDataCompanyByCompany($id_company);
    $response->getBody()->write(json_encode($company, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addCompany', function (Request $request, Response $response, $args) use ($companyDao) {
    $dataCompany = $request->getParsedBody();

    if (
        empty($dataCompany['nameCommercial']) && empty($dataCompany['company']) && empty($dataCompany['state']) &&
        empty($dataCompany['city']) && empty($dataCompany['country']) && empty($dataCompany['address']) &&
        empty($dataCompany['telephone']) && empty($dataCompany['nit']) && empty($dataCompany['creador'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese los todos datos');
    else {
        $company = $companyDao->insertCompany($dataCompany);
        if ($company == 1)
            $resp = array('success' => true, 'message' => 'Compañia ingresada correctamente');
        else
            $resp = $company;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateCompany', function (Request $request, Response $response, $args) use ($companyDao) {
    $dataCompany = $request->getParsedBody();

    if (
        empty($dataCompany['nameCommercial']) && empty($dataCompany['company']) && empty($dataCompany['state']) &&
        empty($dataCompany['city']) && empty($dataCompany['country']) && empty($dataCompany['address']) &&
        empty($dataCompany['telephone']) && empty($dataCompany['nit']) && empty($dataCompany['creador'])
    )
        $resp = array('error' => true, 'message' => 'No hubo cambio alguno');
    else {
        $company = $companyDao->updateCompany($dataCompany);
        if ($company == 2)
            $resp = array('success' => true, 'message' => 'Compañia actualizada correctamente');
        else
            $resp = $company;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteCompany/{id_company}', function (Request $request, Response $response, $args) use ($companyDao) {
    $company = $companyDao->deleteCompany($args['id_company']);

    if ($company == null)
        $resp = array('success' => true, 'message' => 'Compañia eliminada correctamente');
    if ($company != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar la compañia, existe información asociada a ella');
    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
