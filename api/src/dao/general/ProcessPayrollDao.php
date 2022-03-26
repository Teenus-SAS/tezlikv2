<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ProcessPayrollDao
{
    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function findAllProcessByPayroll($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT DISTINCT pay.id_process, p.process 
                                        FROM payroll pay 
                                        INNER JOIN process p ON p.id_process = pay.id_process 
                                        WHERE pay.id_company = :id_company");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $processPayroll = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("processPayroll", array('processPayroll' => $processPayroll));
        return $processPayroll;
    }
}
