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
        $stmt = $connection->prepare("SELECT me.id_expenses_distribution, p.reference, p.product, me.units_sold, me.turnover, me.assignable_expense 
                                  FROM expenses_distribution me
                                  INNER JOIN	products p ON p.id_product = me.id_product
                                  WHERE me.id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $expenses = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("expenses", array('expenses' => $expenses));
        return $expenses;
    }

    public function findTotalExpenseByCompany()
    {
        session_start();
        $id_company = $_SESSION['id_company'];

        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM expenses_distribute
                                      WHERE id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $totalExpense = $stmt->fetch($connection::FETCH_ASSOC);
        $this->logger->notice("expenses", array('expenses' => $totalExpense));
        return $totalExpense;
    }

    public function insertExpensesDistributionByCompany($dataExpensesDistribution, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $unitsSold = str_replace('.', '', $dataExpensesDistribution['unitsSold']);
        $turnover = str_replace('.', '', $dataExpensesDistribution['turnover']);

        /* Calcular gasto asignable por producto */

        $assignableExpense = $this->distributionExpenses($unitsSold, $turnover);

        /* Insertar data */

        try {
            $stmt = $connection->prepare("INSERT INTO expenses_distribution (id_product, id_company, units_sold, 
                                                                            turnover, assignable_expense)
                                          VALUES (:id_product, :id_company, :units_sold, :turnover, :assignable_expense)");
            $stmt->execute([
                'id_product' => $dataExpensesDistribution['selectNameProduct'],
                'id_company' => $id_company,
                'units_sold' => $unitsSold,
                'turnover' => $turnover,
                'assignable_expense' => $assignableExpense
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
        session_start();
        $connection = Connection::getInstance()->getConnection();

        $unitsSold = str_replace('.', '', $dataExpensesDistribution['unitsSold']);
        $turnover = str_replace('.', '', $dataExpensesDistribution['turnover']);

        /* Calcular gasto asignable por producto */

        $assignableExpense = $this->distributionExpenses($unitsSold, $turnover);

        try {
            $stmt = $connection->prepare("UPDATE expenses_distribution SET id_product = :id_product, units_sold = :units_sold,
                                                                turnover = :turnover, assignable_expense = :assignable_expense
                                          WHERE id_expenses_distribution = :id_expenses_distribution");
            $stmt->execute([
                'id_expenses_distribution' => $dataExpensesDistribution['idExpensesDistribution'],
                'id_product' => $dataExpensesDistribution['selectNameProduct'],
                'units_sold' => $unitsSold,
                'turnover' => $turnover,
                'assignable_expense' => $assignableExpense
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteExpensesDistribution($id_expenses_distribution)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM expenses_distribution WHERE id_expenses_distribution = :id_expenses_distribution");
        $stmt->execute(['id_expenses_distribution' => $id_expenses_distribution]);
        $row = $stmt->rowCount();

        if ($row > 0) {
            $stmt = $connection->prepare("DELETE FROM expenses_distribution WHERE id_expenses_distribution = :id_expenses_distribution");
            $stmt->execute(['id_expenses_distribution' => $id_expenses_distribution]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }

        //$assignableExpense = $this->distributionExpenses($unitsSold, $turnover);
    }

    public function distributionExpenses($unitsSold, $turnover)
    {
        //session_start();
        $id_company = $_SESSION['id_company'];

        $connection = Connection::getInstance()->getConnection();

        /* Calcular el total de unidades vendidas y volumen de ventas */

        $stmt = $connection->prepare("SELECT SUM(units_sold) as units_sold, SUM(turnover) as turnover 
                                      FROM expenses_distribution WHERE id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);
        $totalUnitVol = $stmt->fetch($connection::FETCH_ASSOC);


        /* Obtener el total de gastos */

        $stmt = $connection->prepare("SELECT * FROM expenses_distribute WHERE id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);
        $totalExpense = $stmt->fetch($connection::FETCH_ASSOC);

        /* Calcula el gasto asignable */

        $percentageUnitSolds =  $unitsSold / $totalUnitVol['units_sold'];
        $percentageVolSolds = $turnover / $totalUnitVol['turnover'];
        $average = ($percentageUnitSolds + $percentageVolSolds) / 2;

        $averageExpense = $average * $totalExpense['total_expense'];
        $assignableExpense = $averageExpense / $unitsSold;
        return $assignableExpense;
    }
}
