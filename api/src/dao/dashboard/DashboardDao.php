<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class DashboardDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    // Gastos productos
    public function findPricesDashboardProductsCost($dataPrice, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT cost_materials, cost_workforce, cost_indirect_cost, profitability 
                                      FROM products_costs WHERE id_product = :id_product AND id_company = :id_company");
        $stmt->execute(['id_product' => $dataPrice['idProduct'], 'id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $pricesProductsCost = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("prices", array('prices' => $pricesProductsCost));
        return $pricesProductsCost;
    }

    public function findPricesDashboardExpensesDistribution($dataPrice, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare(["SELECT units_sold, turnover, assignable_expense 
                                      FROM expenses_distribution WHERE id_product = :id_product AND id_company = :id_company"]);
        $stmt->execute(['id_product' => $dataPrice['idProduct'], 'id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $pricesExpensesDistribution = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("prices", array('prices' => $pricesExpensesDistribution));
        return $pricesExpensesDistribution;
    }

    //Gastos generales
    public function findAllPricesDashboardGeneralsByCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT pc.process, py.minute_value FROM payroll py 
                                        INNER JOIN process pc ON pc.id_process = py.id_process
                                        WHERE py.id_company = :id_company");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $generalExpenses = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("expenses", array('expenses' => $generalExpenses));
        return $generalExpenses;
    }
}
