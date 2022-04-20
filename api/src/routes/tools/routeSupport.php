<?php

use tezlikv2\dao\SupportDao;

$supportDao = new SupportDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/sendEmailSupport', function (Request $request, Response $response, $args) use ($supportDao) {
    $dataSupport = $request->getParsedBody();

    /*sendEmail($email, $passwordTemp);
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


    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');*/
});
