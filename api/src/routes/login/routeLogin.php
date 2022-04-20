<?php

use tezlikv2\dao\AutenticationUserDao;
use tezlikv2\dao\LicenseCompanyDao;
//use tezlikv2\services\sendEmail;

$licenseDao = new LicenseCompanyDao();
$autenticationDao = new AutenticationUserDao();
//$sendEmail = new sendEmail();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Autenticación */

$app->post('/userAutentication', function (Request $request, Response $response, $args) use ($autenticationDao, $licenseDao) {
    $parsedBody = $request->getParsedBody();

    $user = $parsedBody["validation-email"];
    $password = $parsedBody["validation-password"];
    $user = $autenticationDao->findByEmail($user);

    $resp = array();

    /* Usuario sn datos */
    if ($user == null) {
        $resp = array('error' => true, 'message' => 'Usuario y/o password incorrectos, valide nuevamente');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /* Valida el password del usuario */

    if ($user['password'] != hash("sha256", $password)) {

        $resp = array('error' => true, 'message' => 'Usuario y/o password incorrectos, valide nuevamente');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }


    /* valide licenciamiento empresa */

    $license = $licenseDao->findLicense($user['id_company']);

    if ($license == 0) {
        $resp = array('error' => true, 'message' => 'Su licencia ha caducado, lo invitamos a comunicarse con nosotros');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /* Validar que el usuario es activo */

    if ($user['active'] != 1) {
        $resp = array('error' => true, 'message' => 'Usuario Inactivo, valide con el administrador');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }


    /* Valida la session del usuario */

    if ($user['session_active'] != 0) {
        $resp = array('error' => true, 'message' => 'Usuario con sesión abierta, cierre esa sesion para abrir una nueva');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /* Nueva session */

    session_start();
    $_SESSION['active'] = true;
    $_SESSION['idUser'] = $user['id_user'];
    $_SESSION['name'] = $user['firstname'];
    $_SESSION['lastname'] = $user['lastname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['rol'] = $user["id_rols"];
    $_SESSION['id_company'] = $user['id_company'];
    $_SESSION["time"] = time();

    /* Falta Modificar el estado de la sesion del usuario en BD */

    $resp = array('success' => true, 'message' => 'access granted');
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

/* Logout */

$app->get('/logout', function (Request $request, Response $response, $args) use ($autenticationDao) {
    session_start();
    session_destroy();
    $response->getBody()->write(json_encode("1", JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
