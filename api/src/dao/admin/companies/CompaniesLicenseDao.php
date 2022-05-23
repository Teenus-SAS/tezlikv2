<?php

namespace tezlikv2\dao;

use DateTime;
use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CompaniesLicenseDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }


    //Obtener datos de licencia y empresa activas
    public function findCompanyLicense()
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cp.nit, cp.company, cl.license_start, cl.license_end, cl.quantity_user, cl.status
                                      FROM companies cp 
                                      INNER JOIN companies_licenses cl ON cp.id_company = cl.id_company");
        $stmt->execute();
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $licenses = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $licenses));

        return $licenses;
    }

    //Obtener datos de licencia y empresa inactivas


    //OBTENER DIAS QUE QUEDAN PARA TERMINAR LA LICENCIA empresas activas
    public function findLicenseDays($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cl.license_end FROM companies_licenses cl WHERE cl.id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $licenseData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $licenseData));

        $today  = new DateTime(date('Y-m-d'));
        $licenseEnd = new DateTime($licenseData[0]['license_end']);
        $days = $today->diff($licenseEnd);

        return $days->days;
    }
}
