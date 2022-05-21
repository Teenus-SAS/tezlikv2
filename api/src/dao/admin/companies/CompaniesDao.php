<?php

namespace tezlikv2\dao;

use DateTime;
use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CompaniesDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }


    //OBTENER TODAS LAS EMPRESAS
    public function findCompany()
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM companies");
        $stmt->execute();
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $companyData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $companyData));

        return $companyData;
    }
}
