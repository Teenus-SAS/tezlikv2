<?php

use tezlikv2\dao\SupportDao;

$supportDao = new SupportDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/sendEmailSupport', function (Request $request, Response $response, $args) use ($supportDao) {
    $dataSupport = $request->getParsedBody();
    $email = $_SESSION['email'];

    if (empty($dataSupport['ccHeader']) || empty($dataSupport['subject']) || empty($dataSupport['message'])) {
        $resp = array('error' => true, 'message' => 'Porfavor ingrese todos los campos');
        break;
    } else
        $support = $supportDao->sendEmailSupport($dataSupport, $email);

    if ($support == null)
        $resp = array('success' => true, 'message' => 'El correo a sido enviado, verifique su email');
    else
        $resp = array('error' => true, 'message' => 'Ocurrio un error al enviar el correo. Intente nuevamente');

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
