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


    //Obtener todas las empresas activas
    public function findAllActiveCompanies()
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT cp.company, cp.state, cp.city, cp.country, cp.address, cp.telephone,
                                             cp.nit, cp.logo, cp.created_at, cp.creador, cl.license_start,
                                             cl.license_end, cl.quantity_user FROM companies cp 
                                      INNER JOIN companies_licenses cl ON cp.id_company = cl.id_company 
                                      WHERE cl.status = 1");
        $stmt->execute();
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $companyData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("AllCompanies", array('AllCompanies' => $companyData));

        return $companyData;
    }

    //Obtener todas las empresas inactivas
    public function findAllInactiveCompanies()
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT cp.company, cp.state, cp.city, cp.country, cp.address, cp.telephone,
                                             cp.nit, cp.logo, cp.created_at, cp.creador, cl.license_start,
                                             cl.license_end, cl.quantity_user FROM companies cp 
                                      INNER JOIN companies_licenses cl ON cp.id_company = cl.id_company 
                                      WHERE cl.status = 0");
        $stmt->execute();
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $companyData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("AllCompanies", array('AllCompanies' => $companyData));

        return $companyData;
    }
}
