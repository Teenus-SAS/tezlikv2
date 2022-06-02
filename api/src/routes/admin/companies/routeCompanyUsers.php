<?php

use tezlikv2\dao\CompanyUsers;

$companyUsers = new CompanyUsers();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//Obtener usuarios por empresa
$app->get('/companyUsers/{idCompany}', function (Request $request, Response $response, $args) use ($companyUsers) {
    $resp = $companyUsers->findCompanyUsers($args['id']);
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


// //Nueva Empresa
// $app->post('/addNewCompany', function (Request $request, Response $response, $args) use ($companyUsers) {
//     $dataCompany = $request->getParsedBody();
//     /*Agregar datos a companies */
//     $idcompany = $companiesDao->addCompany($dataCompany);
//     /*Agregar datos a companies licenses*/
//     $company = $companiesLicDao->addLicense($dataCompany, $idcompany['idCompany']);

//     if ($company == null) {
//         $resp = array('success' => true, 'message' => 'Datos de Empresa agregados correctamente');
//     } else {
//         $resp = array('error' => true, 'message' => 'Ocurrio un error al actualizar la licencia. Intente nuevamente');
//     }

//     $response->getBody()->write(json_encode($resp));
//     return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
// });
