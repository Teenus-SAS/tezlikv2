<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class FactoryLoadDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllFactoryLoadByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM manufacturing_load WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $factoryloads = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("factory load", array('factory load' => $factoryloads));
    return $factoryloads;
  }

  public function insertFactoryLoadByCompany($dataFactoryLoad, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("INSERT INTO manufacturing_load (id_machine, id_company, input, cost, cost_minute)
                                    VALUES (:id_machine, :id_company, :input, :cost, :cost_minute)");
      $stmt->execute([
        'id_machine' => $dataFactoryLoad['idMachine'],
        'id_company' => $id_company,
        'input' => ucfirst(strtolower($dataFactoryLoad['description'])),
        'cost' => $dataFactoryLoad['cost'],
        'cost_minute' => $dataFactoryLoad['costMinute']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updateFactoryLoad($dataFactoryLoad)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("UPDATE manufacturing_load SET id_machine = :id_machine, input = :input, cost = :cost, cost_minute = :cost_minute
                                    WHERE id_manufacturing_load = :id_manufacturing_load");
      $stmt->execute([
        'id_manufacturing_load' => $dataFactoryLoad['idManufacturingLoad'],
        'id_machine' => $dataFactoryLoad['idMachine'],
        'input' => ucfirst(strtolower($dataFactoryLoad['description'])),
        'cost' => $dataFactoryLoad['cost'],
        'cost_minute' => $dataFactoryLoad['costMinute']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deleteFactoryLoad($id_manufacturing_load)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM manufacturing_load WHERE id_manufacturing_load = :id_manufacturing_load");
    $stmt->execute(['id_manufacturing_load' => $id_manufacturing_load]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM manufacturing_load WHERE id_manufacturing_load = :id_manufacturing_load");
      $stmt->execute(['id_manufacturing_load' => $id_manufacturing_load]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
