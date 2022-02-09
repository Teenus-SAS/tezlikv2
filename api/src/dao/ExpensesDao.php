<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ExpensesDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllExpensesByCompany()
  {
    session_start();
    $id_company = $_SESSION['id_company'];
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT me.id_expenses, p.reference, p.product, me.units_sold, me.turnover, me.assignable_expense 
                                  FROM monthly_expenses me
                                  INNER JOIN	products p ON p.id_product = me.id_product
                                  WHERE me.id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);
    
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    
    $expenses = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("process", array('process' => $expenses));
    return $expenses;
  }

}
