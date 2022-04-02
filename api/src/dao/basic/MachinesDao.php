<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class MachinesDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllMachinesByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM machines WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $machines = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("machines", array('machines' => $machines));
    return $machines;
  }

  public function insertMachinesByCompany($dataMachine, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $costMachine = str_replace('.', '', $dataMachine['cost']);
    $residualValue = str_replace('.', '', $dataMachine['residualValue']);

    try {
      $stmt = $connection->prepare("INSERT INTO machines (id_company ,machine, cost, years_depreciation, 
                                                minute_depreciation, residual_value, hours_machine, days_machine) 
                                    VALUES (:id_company ,:machine, :cost, :years_depreciation,
                                    :minute_depreciation, :residual_value, :hours_machine, :days_machine)");
      $stmt->execute([
        'id_company' => $id_company,
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $costMachine,
        'years_depreciation' => $dataMachine['depreciationYears'],
        'minute_depreciation' => $dataMachine['depreciationMinute'],
        'residual_value' => $residualValue,
        'hours_machine' => $dataMachine['hoursMachine'],
        'days_machine' => $dataMachine['daysMachine']
      ]);

      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    } catch (\Exception $e) {

      $message = $e->getMessage();

      if ($e->getCode() == 23000)
        $message = 'Referencia duplicada. Ingrese una nueva referencia';

      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updateMachine($dataMachine)
  {
    $connection = Connection::getInstance()->getConnection();
    $costMachine = str_replace('.', '', $dataMachine['cost']);
    $residualValue = str_replace('.', '', $dataMachine['residualValue']);

    try {
      $stmt = $connection->prepare("UPDATE machines SET machine = :machine, cost = :cost, years_depreciation = :years_depreciation,
                                       minute_depreciation = :minute_depreciation, residual_value = :residual_value , 
                                       hours_machine = :hours_machine, days_machine =:days_machine   
                                    WHERE id_machine = :id_machine");
      $stmt->execute([
        'id_machine' => $dataMachine['idMachine'],
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $costMachine,
        'years_depreciation' => $dataMachine['depreciationYears'],
        'minute_depreciation' => $dataMachine['depreciationMinute'],
        'residual_value' => $residualValue,
        'hours_machine' => $dataMachine['hoursMachine'],
        'days_machine' => $dataMachine['daysMachine']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deleteMachine($id_machine)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM machines WHERE id_machine = :id_machine");
    $stmt->execute(['id_machine' => $id_machine]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM machines WHERE id_machine = :id_machine");
      $stmt->execute(['id_machine' => $id_machine]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
