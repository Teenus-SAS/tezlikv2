<?php

use tezlikv2\dao\UsersDao;

$userDao = new UsersDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/users', function (Request $request, Response $response, $args) use ($userDao) {
    session_start();
    $company = $_SESSION['id_company'];
    $users = $userDao->findAllusersByCompany($company);
    $response->getBody()->write(json_encode($users, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/user', function (Request $request, Response $response, $args) use ($userDao) {
    $users = $userDao->findUser();
    $response->getBody()->write(json_encode($users, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Insertar y actualizar usuario */
$app->post('/addUser', function (Request $request, Response $response, $args) use ($userDao) {
    $dataUser = $request->getParsedBody();


    if (empty($dataUser['names']) && empty($dataUser['lastnames']) && empty($dataUser['email'])) /* { */
        $resp = array('error' => true, 'message' => 'Complete todos los datos');

    $users = $userDao->saveUser($dataUser);

    if ($users == 1)
        $resp = array('error' => true, 'message' => 'El email ya se encuentra registrado. Intente con uno nuevo');

    if ($users == 2)
        $resp = array('success' => true, 'message' => 'Usuario creado correctamente');

    if ($users == 3)
        $resp = array('success' => true, 'message' => 'Usuario actualizado correctamente');
    /* } */

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateUser', function (Request $request, Response $response, $args) use ($userDao) {
    $dataUser = $request->getParsedBody();
    $files = $request->getUploadedFiles();

    if (empty($dataUser['names']) && empty($dataUser['lastnames'])) {
        $resp = array('error' => true, 'message' => 'Ingrese sus Nombres y Apellidos completos');
    } else {
        $cont = 1;
        foreach ($files as $file) {
            $name = $file->getClientFilename();
            $name = explode(".", $name);
            $ext = array_pop($name);
            $ext = strtolower($ext);

            if (empty($ext)) {
                $path = null;
                if ($cont == 2)
                    $users = $userDao->updateUser($dataUser, $path, $cont);
                $cont = $cont + 1;
            } else {

                if (!in_array($ext, ["jpeg", "jpg", "png"])) {
                    $resp = array('error' => true, 'message' => 'La imagen cargada no es valida');
                    $response->getBody()->write(json_encode($resp));
                    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
                } else {
                    if ($cont == 1) {
                        $file->moveTo("../app/assets/images/avatars/" . $name[0] . '.' . $ext);
                        $path = "../../../app/assets/images/avatars/" . $name[0] . '.' . $ext;
                    } else {
                        $file->moveTo("../app/assets/images/signatures/" . $name[0] . '.' . $ext);
                        $path = "../../../app/assets/images/signatures/" . $name[0] . '.' . $ext;
                    }
                    $users = $userDao->updateUser($dataUser, $path, $cont);
                    $cont = $cont + 1;
                }
            }
        }

        $cont = 1;
        if ($users == 1)
            $resp = array('success' => true, 'message' => 'Usuario actualizado correctamente');
        else
            $resp = array('error' => true, 'message' => 'Ocurrio un error, Intente nuevamente');
    }

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

/* Eliminar Usuario */
$app->post('/deleteUser', function (Request $request, Response $response, $args) use ($userDao) {
    $dataUser = $request->getParsedBody();
    $users = $userDao->deleteUser($dataUser);
    $response->getBody()->write(json_encode($users, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Inactivar/Activar Usuario */
$app->get('/inactivateActivateUser/{id}', function (Request $request, Response $response, $args) use ($userDao) {
    $users = $userDao->inactivateActivateUser($args['id']);
    if ($users == 0)
        $resp = array('info' => true, 'message' => 'Usuario inactivado correctamente');

    if ($users == 1)
        $resp = array('success' => true, 'message' => 'Usuario activado correctamente');

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


/* Auntenticación */

$app->post('/user', function (Request $request, Response $response, $args) use ($userDao) {
    $parsedBody = $request->getParsedBody();

    $user = $parsedBody["validation-email"];
    $password = $parsedBody["validation-password"];
    $user = $userDao->findByEmail($user);

    $resp = array();
    if ($user != null) {
        if ($user['password'] == hash("sha256", $password)) {
            if ($user['active'] == 1) {
                if ($user['session_active'] == 0) {
                    session_start();
                    $_SESSION['active'] = true;
                    $_SESSION['idUser'] = $user['id_user'];
                    $_SESSION['name'] = $user['firstname'];
                    $_SESSION['lastname'] = $user['lastname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['rol'] = $user["id_rols"];
                    $_SESSION['id_company'] = $user['id_company'];
                    $_SESSION["timeout"] = time();

                    $response->getBody()->write(json_encode($user['id_company'], JSON_NUMERIC_CHECK));
                    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
                } else {
                    $resp = array('error' => true, 'message' => 'Usuario con sesión abierta, cierre esa sesion para abrir una nueva');
                    $response->getBody()->write(json_encode($resp));
                    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
                }
            } else {
                $resp = array('error' => true, 'message' => 'Usuario Inactivo, valide con el administrador');
                $response->getBody()->write(json_encode($resp));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }
        } else {
            $resp = array('error' => true, 'message' => 'Usuario y/o password incorrectos, valide nuevamente');
            $response->getBody()->write(json_encode($resp));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
    } else {
        $resp = array('error' => true, 'message' => 'Usuario y/o password incorrectos, valide nuevamente');
        $response->getBody()->write(json_encode($resp));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
});


/* Change Password */

$app->post('/changePassword', function (Request $request, Response $response, $args) use ($userDao) {
    session_start();
    $id = $_SESSION['idUser'];

    if ($id != null) {

        $parsedBody = $request->getParsedBody();
        $newpass = $parsedBody["inputNewPass"];
        $newpass1 = $parsedBody["inputNewPass1"];

        if ($newpass != $newpass1)
            $resp = array('error' => true, 'message' => 'Las contraseñas no coinciden, intente nuevamente');
        else {
            $usersChangePassword = $userDao->ChangePasswordUser($id, $newpass);

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

$app->post('/forgotPassword', function (Request $request, Response $response, $args) use ($userDao) {
    $parsedBody = $request->getParsedBody();
    $email = trim($parsedBody["data"]);

    $passwordTemp = $userDao->forgotPasswordUser($email);

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

$app->get('/logout', function (Request $request, Response $response, $args) use ($userDao) {
    session_start();
    session_destroy();
    $response->getBody()->write(json_encode("1", JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
