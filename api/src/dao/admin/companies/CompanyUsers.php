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


    //Obtener todos los usuarios de cada empresa
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

   
}
