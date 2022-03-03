<?php

use tezlikv2\dao\autenticationDao;
//use tezlikv2\services\sendEmail;

$autenticationDao = new autenticationDao();
//$sendEmail = new sendEmail();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta para acceso de todos los usuarios */

$app->get('/usersAccess', function (Request $request, Response $response, $args) use ($autenticationDao) {
    session_start();
    $company = $_SESSION['id_company'];
    $usersAccess = $autenticationDao->findAllUsersAccess($company);
    $response->getBody()->write(json_encode($usersAccess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Consulta para acceso de un usuario */

$app->post('/userAccess', function (Request $request, Response $response, $args) use ($autenticationDao) {
    session_start();
    $company = $_SESSION['id_company'];
    $id_user = $_SESSION['idUser'];
    $usersAccess = $autenticationDao->findUserAccess($company, $id_user);
    $response->getBody()->write(json_encode($usersAccess, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Autenticación */

$app->post('/userAutentication', function (Request $request, Response $response, $args) use ($autenticationDao) {
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

    $license = $autenticationDao->findLicense($user['id_company']);

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
    
    $resp = array('success' => true, 'message' => 'access granted');
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


/* Change Password */

$app->post('/changePassword', function (Request $request, Response $response, $args) use ($autenticationDao) {
    session_start();
    $id = $_SESSION['idUser'];

    if ($id != null) {

        $parsedBody = $request->getParsedBody();
        $newpass = $parsedBody["inputNewPass"];
        $newpass1 = $parsedBody["inputNewPass1"];

        if ($newpass != $newpass1)
            $resp = array('error' => true, 'message' => 'Las contraseñas no coinciden, intente nuevamente');
        else {
            $usersChangePassword = $autenticationDao->ChangePasswordUser($id, $newpass);

            if ($usersChangePassword == null)
                $resp = array('success' => true, 'message' => 'Cambio de Password correcto');
            else
                $resp = array('error' => true, 'message' => 'Hubo un problema, intente nuevamente');
        }
    } else
        $resp = array('error' => true, 'message' => 'Usuario no autorizado');

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

/* Forgot Password */

$app->post('/forgotPassword', function (Request $request, Response $response, $args) use ($autenticationDao) {
    $parsedBody = $request->getParsedBody();
    $email = trim($parsedBody["data"]);

    $passwordTemp = $autenticationDao->forgotPasswordUser($email);

    if ($passwordTemp == null)
        $resp = array('success' => true, 'message' => 'La contraseña fue enviada al email suministrado exitosamente');
    else {
        //sendEmail($email, $passwordTemp);
        // the message
        $msg = "Hola,<br><br>
            Recientemente solicitó recordar su contraseña por lo que para mayor seguridad creamos una nueva. Para ingresar al CRM puede hacerlo con:
            <ul>
            <li>Nombre de usuario: $email</li>
            <li>Contraseña: $passwordTemp</li>
            </ul>
             
            Las contraseñas generadas a través de nuestra plataforma son muy seguras solo se envían al correo electrónico del contacto de la cuenta.<br><br> 
            Si le preocupa la seguridad de la cuenta o sospecha que alguien está intentando obtener acceso no autorizado, puede estar 
            seguro que las contraseñas son generadas aleatoriamente, sin embargo, le recomendamos ingresar a la plataforma con la nueva clave y cambiarla por una nueva.<br><br>
        
            Saludos,<br><br>
        
            El Equipo de Soporte CRM";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        //headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: SoporteCRM <soporteCRM@proyecformas.com>' . "\r\n";
        // send email
        mail($email, "Nuevo password", $msg, $headers);

        $resp = array('success' => true, 'message' => 'La contraseña fue enviada al email suministrado exitosamente');
    }

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
