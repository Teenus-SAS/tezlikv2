<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CompanyUsers
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }


    //Obtener todos los usuarios * empresa
    public function findCompanyUsers($idCompany)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cp.company, us.firstname, us.lastname, us.email, us.active 
                                      FROM companies cp INNER JOIN users us ON cp.id_company = us.id_company
                                      WHERE cp.id_company = :id_company");
        $stmt->execute(['id_company' => $idCompany]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $licenses = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses", array('licenses' => $licenses));

        return $licenses;
    }

    //Actualizar Estado de usuarios * empresa
    public function updateCompanyUsersStatus($dataUser)
    {
        $connection = Connection::getInstance()->getConnection();
        try {
            $stmt = $connection->prepare("UPDATE users SET active = :active WHERE id_user = :id_user");
            $stmt->execute([
                'active' => $dataUser['status'],              
                'id_user' => $dataUser['id_user'],
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

   
}
