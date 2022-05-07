<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class SendCodeDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function NewCode()
    {
        $cadena = "0123456789";
        $longitudCadena = strlen($cadena);
        $new_code = "";
        //$longitudPass = 6;

        // for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $new_code .= substr($cadena, $pos, 1);


        /* Enviar $new_pass */
        return $new_code;
    }

    public function SendCodeByEmail($new_code, $user)
    {
        // $new_code = $this->NewCode();

        $name = $user['firstname'];
        $to = $user['email'];

        $msg = "Hola $name<br><br>
                Si estas tratando de iniciar sesion en Tezlik. <br>
                Ingresa el siguiente código para completar el inicio de sesión:<br><br>
                <h4>$new_code</h4>";
        $msg = wordwrap($msg, 70);

        // Headers
        $headers = "Tu código de verificación de inicio de sesión";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: SoporteTeenus <soporte@teenus.com.co>" . "\r\n";

        // send email
        mail($to, "Soporte", $msg, $headers);
    }

    /* public function CheckCode($dataCheck)
    {
    }*/
}
