<?php

use tezlikv2\dao\SendCodeDao;

$sendCodeDao = new SendCodeDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Enviar email */

$app->get('/sendEmail', function (Request $request, Response $response, $args) use ($sendCodeDao) {
    session_start();
    $user['firstname'] = $_SESSION['name'];
    $user['email'] = $_SESSION['email'];

    // Crear codigo y enviarlo en email
    $code = $sendCodeDao->NewCode();
    $sendCode = $sendCodeDao->SendCodeByEmail($code, $user);
    // Guardar codigo
    $_SESSION['code'] = $code;

    if ($sendCode == null) $resp = array('success' => true);

    $response->getBody()->write(json_encode($resp, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});

/* Verificar codigo */

$app->post('/checkCode', function (Request $request, Response $response, $args) {
    session_start();
    $code = $_SESSION['code'];
    $dataCheck = $request->getParsedBody();

    if ($dataCheck['code'] == $code) $resp = array('success' => true, 'message' => 'Inicio de sesión completado');
    else $resp = array('error' => true, 'message' => 'Codigo incorrecto, ingrese el codigo nuevamente');
    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
