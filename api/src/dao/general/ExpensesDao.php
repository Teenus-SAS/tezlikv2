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
    $stmt = $connection->prepare("SELECT p.number_count, p.count, e.value 
                                  FROM expenses e 
                                  INNER JOIN puc p ON e.id_puc = p.id_puc 
                                  WHERE e.id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $expenses = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("expenses", array('expenses' => $expenses));
    return $expenses;
  }

  public function insertExpensesByCompany($dataExpenses, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("INSERT INTO expenses (id_puc, id_company, value)
                                    VALUES (:id_puc, :id_company, :value)");
      $stmt->execute([
        'id_puc' => $dataExpenses['idPuc'],
        'id_company' => $id_company,
        'value' => $dataExpenses['value']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      if ($e->getCode() == 23000)
        $message = 'No. Cuenta duplicada. Ingrese un nuevo No. Cuenta';
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updateExpenses($dataExpenses)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("UPDATE expenses SET id_puc = :id_puc, value = :value
                                      WHERE id_expenses = :id_expenses");
      $stmt->execute([
        'id_puc' => $dataExpenses['idPuc'],
        'value' => $dataExpenses['value'],
        'id_expenses' => $dataExpenses['idExpenses']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deleteExpenses($id_expenses)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM expenses WHERE id_expenses = :id_expenses");
    $stmt->execute(['id_expenses' => $id_expenses]);
    $row = $stmt->rowCount();

    if ($row > 0) {
      $stmt = $connection->prepare("DELETE FROM expenses WHERE id_expenses = :id_expenses");
      $stmt->execute(['id_expenses' => $id_expenses]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
