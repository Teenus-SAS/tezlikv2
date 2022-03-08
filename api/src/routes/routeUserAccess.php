<?php

use tezlikv2\dao\userAccessDao;

$userAccessDao = new userAccessDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta para acceso de todos los usuarios */

$app->get('/usersAccess', function (Request $request, Response $response, $args) use ($userAccessDao) {
    session_start();
    $company = $_SESSION['id_company'];
    $usersAccess = $userAccessDao->findAllUsersAccess($company);
    $response->getBody()->write(json_encode($usersAccess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Consulta para acceso de un usuario */

$app->post('/userAccess', function (Request $request, Response $response, $args) use ($userAccessDao) {
    session_start();
    $company = $_SESSION['id_company'];
    $id_user = $_SESSION['idUser'];
    $usersAccess = $userAccessDao->findUserAccess($company, $id_user);
    $response->getBody()->write(json_encode($usersAccess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addUserAccess', function (Request $request, Response $response, $args) use ($userAccessDao) {
    session_start();
    $dataUserAccess = $request->getParsedBody();
    $id_user = $_SESSION['idUser'];

    if (
        empty($dataUserAccess['createProduct']) && empty($dataUserAccess['createMaterials']) &&
        empty($dataUserAccess['createMachines']) && empty($dataUserAccess['createProcess'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $userAccess = $userAccessDao->insertUserAccessByUsers($dataUserAccess, $id_user);

        if ($userAccess == 1)
            $resp = array('success' => true, 'message' => 'Acceso de usuario creado correctamente');
        else
            $resp = $userAccess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateUserAccess', function (Request $request, Response $response, $args) use ($userAccessDao) {
    session_start();
    $dataUserAccess = $request->getParsedBody();
    $id_user = $_SESSION['idUser'];

    if (
        empty($dataUserAccess['createProduct']) && empty($dataUserAccess['createMaterials']) &&
        empty($dataUserAccess['createMachines']) && empty($dataUserAccess['createProcess'])
    )
        $resp = array('error' => true, 'message' => 'Ingrese todos los datos');
    else {
        $userAccess = $userAccessDao->updateUserAccessByUsers($dataUserAccess, $id_user);

        if ($userAccess == 2)
            $resp = array('success' => true, 'message' => 'Acceso de usuario actualizado correctamente');
        else
            $resp = $userAccess;
    }
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
