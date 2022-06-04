<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CompaniesStatusDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }    

    //Cambiar estado de licencia
    public function statusLicense($dataLicense)
    {
        $connection = Connection::getInstance()->getConnection();
        try {
            $stmt = $connection->prepare("UPDATE companies_licenses SET status = :status 
                                          WHERE id_company = :id_company");
            $stmt->execute([
                'status' => $dataLicense['status'],
                'id_company' => $dataLicense['id_company'],
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }
}
