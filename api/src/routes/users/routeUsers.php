<?php

use tezlikv2\dao\UsersDao;

$userDao = new UsersDao();

// Cantidad de usuarios
use tezlikv2\dao\QuantityUsersDao;

$quantityUsersDao = new QuantityUsersDao();

//Acceso de usuario
use tezlikv2\dao\AccessUserDao;

$AccesuserDao = new AccessUserDao();

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

/* Insertar usuario */

$app->post('/addUser', function (Request $request, Response $response, $args) use ($userDao, $quantityUsersDao, $AccesuserDao) {
    session_start();
    $id_company = $_SESSION['id_company'];
    $dataUser = $request->getParsedBody();

    $quantityAllowsUsers = $quantityUsersDao->quantityUsersAllows($id_company);
    $quantityCreatedUsers = $quantityUsersDao->quantityUsersCreated($id_company);


    if ($quantityAllowsUsers >= $quantityCreatedUsers)
        $resp = array('error' => true, 'message' => 'Cantidad de usuarios maxima alcanzada');
    else {
        if (empty($dataUser['nameUser']) && empty($dataUser['lastnameUser']) && empty($dataUser['emailUser'])) /* { */
            $resp = array('error' => true, 'message' => 'Complete todos los datos');

        /* Almacena el usuario */
        $users = $userDao->saveUser($dataUser, $id_company);

        /* Almacene los acceso */
        $usersAccess = $AccesuserDao->insertUserAccessByUsers($dataUser);

        if ($users == 1)
            $resp = array('error' => true, 'message' => 'El email ya se encuentra registrado. Intente con uno nuevo');

        if ($users == 2)
            $resp = array('success' => true, 'message' => 'Usuario creado correctamente');

        // if ($users == 3)
        //     $resp = array('success' => true, 'message' => 'Usuario actualizado correctamente');
    }
    /* } */

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->post('/updateUser', function (Request $request, Response $response, $args) use ($userDao, $AccesuserDao) {
    $dataUser = $request->getParsedBody();
    $files = $request->getUploadedFiles();

    if (empty($dataUser['nameUser']) && empty($dataUser['lastnameUser'])) {
        $resp = array('error' => true, 'message' => 'Ingrese sus Nombres y Apellidos completos');
    } else {
        if (empty($dataUser['avatar'])) {
            $users = $userDao->updateUser($dataUser, null);
            /* Actualizar los accesos */
            $usersAccess = $AccesuserDao->updateUserAccessByUsers($dataUser);
        } else {
            foreach ($files as $file) {
                $name = $file->getClientFilename();
                $name = explode(".", $name);
                $ext = array_pop($name);
                $ext = strtolower($ext);
                if (empty($ext)) {
                    $path = null;
                } else {
                    if (!in_array($ext, ["jpeg", "jpg", "png"])) {
                        $resp = array('error' => true, 'message' => 'La imagen cargada no es valida');
                        $response->getBody()->write(json_encode($resp));
                        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
                    } else {
                        $file->moveTo("../app/assets/images/avatars/" . $name[0] . '.' . $ext);
                        $path = "../../../app/assets/images/avatars/" . $name[0] . '.' . $ext;
                        $users = $userDao->updateUser($dataUser, $path);
                        /* Actualizar los accesos */
                        $usersAccess = $AccesuserDao->updateUserAccessByUsers($dataUser);
                        // Creacion carpeta de la img
                        $path = "../../../app/assets/images/avatars/44";
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                    }
                }
            }
        }
    }
    if ($users == 1)
        $resp = array('success' => true, 'message' => 'Usuario actualizado correctamente');
    else
        $resp = array('error' => true, 'message' => 'Ocurrio un error, Intente nuevamente');

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/deleteUser/{idUser}', function (Request $request, Response $response, $args) use ($userDao) {
    $users = $userDao->deleteUser($args['idUser']);
    if ($users == null)
        $resp = array('success' => true, 'message' => 'Usuario eliminado correctamente');

    if ($users != null)
        $resp = array('error' => true, 'message' => 'No es posible eliminar el usuario');

    $response->getBody()->write(json_encode($resp));
    return $response->withHeader('Content-Type', 'application/json');
});
