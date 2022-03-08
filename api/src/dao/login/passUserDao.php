<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PassUserDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function ChangePasswordUser($id_user, $newPass)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM users WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $id_user]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $pass = password_hash($newPass, PASSWORD_DEFAULT);

            $stmt = $connection->prepare("UPDATE users SET pass = :pass WHERE id_user = :id_user");
            $stmt->execute(['id_user' => $id_user, 'pass' => $pass]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }

    public function forgotPasswordUser($email)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {

            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $longitudCadena = strlen($cadena);
            $new_pass = "";
            $longitudPass = 6;

            for ($i = 1; $i <= $longitudPass; $i++) {
                $pos = rand(0, $longitudCadena - 1);
                $new_pass .= substr($cadena, $pos, 1);
            }

            /* actualizar $pass en la DB */
            $pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("UPDATE users SET pass = :pass WHERE email = :email");
            $stmt->execute(['email' => $email, 'pass' => $pass]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

            /* Enviar $new_pass por email */
            return $new_pass;
        }
    }
}
