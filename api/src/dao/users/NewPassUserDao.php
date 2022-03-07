<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class NewPassUserDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public static function NewPassUser()
    {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $longitudCadena = strlen($cadena);
        $new_pass = "";
        $longitudPass = 6;

        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $new_pass .= substr($cadena, $pos, 1);
        }

        /* Enviar $new_pass */
        return $new_pass;
    }
}
