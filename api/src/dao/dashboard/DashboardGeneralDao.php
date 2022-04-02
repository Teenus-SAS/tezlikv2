<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class DashboardGeneralDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    //Gastos generales
    public function findProcessMinuteValueByCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT pc.process, py.minute_value FROM payroll py 
                                        INNER JOIN process pc ON pc.id_process = py.id_process
                                        WHERE py.id_company = :id_company");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $processMinuteValue = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("processMinuteValue", array('processMinuteValue' => $processMinuteValue));
        return $processMinuteValue;
    }

    public function findfactoryLoadMinuteValueByCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT m.machine, SUM(ml.cost_minute) AS totalCostMinute
                                      FROM machines m 
                                      INNER JOIN manufacturing_load ml ON ml.id_machine = m.id_machine 
                                      WHERE ml.id_company = :id_company GROUP BY m.machine");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $factoryLoadMinuteValue = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("factoryLoadMinuteValue", array('factoryLoadMinuteValue' => $factoryLoadMinuteValue));
        return $factoryLoadMinuteValue;
    }

    public function findExpensesValueByCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT ex.id_puc, p.number_count, SUM(ex.expense_value) as totalExpenseValue
                                      FROM expenses ex
                                      LEFT JOIN puc p ON p.id_puc = ex.id_puc
                                      WHERE ex.id_company = :id_company AND
                                      p.number_count LIKE '51%'"); /* OR p.number_count LIKE '52%' */
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $expenseValue = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("expenseValue", array('expenseValue' => $expenseValue));
        return $expenseValue;
    }
}
