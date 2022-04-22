<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class SupportDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function sendEmailSupport($dataSupport, $email)
    {
        $to = 'soporte@teenus.com.co';
        // the message
        $msg = $dataSupport['message'];

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        //headers
        $headers = $dataSupport['header'];
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: SoporteCRM <$email>" . "\r\n";
        // send email
        mail($to, "Soporte", $msg, $headers);
    }
}
