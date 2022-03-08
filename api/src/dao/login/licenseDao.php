<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class licenseDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function findLicense($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cl.license_end 
                                  FROM company_license cl WHERE cl.id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $licenseData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $licenseData));

        $today = date('Y-m-d');
        $licenseDay = $licenseData[0]['license_end'];
        $today < $licenseDay ? $license = 1 : $license = 0;
        return $license;
    }
}
