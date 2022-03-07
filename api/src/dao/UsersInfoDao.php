<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class UsersInfoDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function inactivateActivateUser($id_user)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM users WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $id_user]);
        $users = $stmt->fetch($connection::FETCH_ASSOC);

        $users['status'] == 0 ? $status = 1 : $status = 0;

        $stmt = $connection->prepare("UPDATE users SET status = :statusUser WHERE id_user = :id_user");
        $stmt->execute([
            'id_user' => $id_user,
            'statusUser' => $status
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return $status;
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
