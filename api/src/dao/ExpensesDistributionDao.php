<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ExpensesDistributionDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function findAllExpensesDistributionByCompany()
    {
        session_start();
        $id_company = $_SESSION['id_company'];
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT me.id_expenses, p.reference, p.product, me.units_sold, me.turnover, me.assignable_expense 
                                  FROM expenses_distribution me
                                  INNER JOIN	products p ON p.id_product = me.id_product
                                  WHERE me.id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $expenses = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("expenses", array('expenses' => $expenses));
        return $expenses;
    }

    public function insertExpensesDistributionByCompany($dataExpensesDistribution, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("INSERT INTO expenses_distribution (id_product, id_company, units_sold, 
                                                                            turnover, assignable_expense)
                                          VALUES (:id_product, :id_company, :units_sold, :turnover, :assignable_expense)");
            $stmt->execute([
                'id_product' => $dataExpensesDistribution['idProduct'],
                'id_company' => $id_company,
                'units_sold' => ucfirst(strtolower($dataExpensesDistribution['unitsSold'])),
                'turnover' => ucfirst(strtolower($dataExpensesDistribution['turnover'])),
                'assignable_expense' => $dataExpensesDistribution['assignableExpense']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e->getCode() == 23000)
                $message = 'Distribucion de gastos duplicado. Ingrese una nueva distribucion';
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function updateExpensesDistribution($dataExpensesDistribution)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE expenses_distribution SET id_product = :id_product, units_sold = :units_sold,
                                                                turnover = :turnover, assignable_expense = :assignable_expense
                                          WHERE id_expenses = :id_expenses");
            $stmt->execute([
                'id_expenses' => $dataExpensesDistribution['idExpenses'],
                'id_product' => $dataExpensesDistribution['idProduct'],
                'units_sold' => ucfirst(strtolower($dataExpensesDistribution['unitsSold'])),
                'turnover' => ucfirst(strtolower($dataExpensesDistribution['turnover'])),
                'assignable_expense' => $dataExpensesDistribution['assignableExpense']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteExpensesDistribution($id_expenses)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM expenses_distribution WHERE id_expenses = :id_expenses");
        $stmt->execute(['id_expenses' => $id_expenses]);
        $row = $stmt->rowCount();

        if ($row > 0) {
            $stmt = $connection->prepare("DELETE FROM expenses_distribution WHERE id_expenses = :id_expenses");
            $stmt->execute(['id_expenses' => $id_expenses]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
