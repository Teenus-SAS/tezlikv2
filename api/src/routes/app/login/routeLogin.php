<?php

use tezlikv2\dao\AutenticationUserDao;
use tezlikv2\dao\LicenseCompanyDao;
use tezlikv2\dao\StatusActiveUserDao;
use tezlikv2\dao\GenerateCodeDao;
use tezlikv2\dao\SendEmailDao;
use tezlikv2\dao\LastLoginDao;

$licenseDao = new LicenseCompanyDao();
$autenticationDao = new AutenticationUserDao();
$statusActiveUserDao = new StatusActiveUserDao();
$generateCodeDao = new GenerateCodeDao();
$sendEmailDao = new SendEmailDao();
$lastLoginDao = new LastLoginDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Autenticación */

$app->post('/userAutentication', function (Request $request, Response $response, $args) use ($autenticationDao, $licenseDao, $statusActiveUserDao, $generateCodeDao, $sendEmailDao, $lastLoginDao) {
    $parsedBody = $request->getParsedBody();

    /* Validar intentos de sesión */

    $tries = file_get_contents('tries.txt');
    settype($tries, 'integer');
    $max_tries = 5;

    if ($tries >= $max_tries) {
        $resp = array('error' => true, 'message' => 'Número máximo de intentos superados. Vuelva a intentar en unos minutos');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    $user = $parsedBody["validation-email"];
    $password = $parsedBody["validation-password"];
    $user = $autenticationDao->findByEmail($user);

    /*Validar usuario y contraseña*/
    if ($user == null || $user['password'] != hash("sha256", $password)) {

        $new_trie = ++$tries;
        file_put_contents('tries.txt', $new_trie);

        $resp = array('error' => true, 'message' => 'Usuario y/o password incorrectos, valide nuevamente');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /* valide licenciamiento empresa */

    $license = $licenseDao->findLicense($user['id_company']);

    if ($license == 0) {
        $resp = array('error' => true, 'message' => 'Su licencia ha caducado, lo invitamos a comunicarse');
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
        $resp = array('error' => true, 'message' => 'Usuario logeado, cierre la sesión para abrir una nueva');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /* Nueva session */
    $new_trie = 0;
    file_put_contents('tries.txt', $new_trie);

    session_start();
    $_SESSION['active'] = true;
    $_SESSION['idUser'] = $user['id_user'];
    $_SESSION['name'] = $user['firstname'];
    $_SESSION['lastname'] = $user['lastname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['rol'] = $user["id_rols"];
    $_SESSION['id_company'] = $user['id_company'];
    $_SESSION["time"] = time();

    /* Actualizar metodo ultimo logueo */
    $lastLoginDao->findLastLogin();


    /* Genera codigo */
    //$code = $generateCodeDao->GenerateCode();
    //$_SESSION["code"] = $code;

    /* Envio el codigo por email */
    //$sendEmailDao->SendEmailCode($code, $user);

    /* Modificar el estado de la sesion del usuario en BD */
    //$statusActiveUserDao->changeStatusUserLogin();

    $user["id_rols"] == 1 ? $location = '../../admin/' : $location = '../../app/';

    $resp = array('success' => true, 'message' => 'Ingresar código', 'location' => $location);
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

/* Logout */

$app->get('/logout', function (Request $request, Response $response, $args) use ($statusActiveUserDao) {
    session_start();
    //$statusActiveUserDao->changeStatusUserLogin();
    session_destroy();
    $response->getBody()->write(json_encode("1", JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/delay', function (Request $request, Response $response, $args) use ($statusActiveUserDao) {
    $new_trie = 0;
    file_put_contents('/tries.txt', $new_trie);
});
