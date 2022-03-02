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

    try {
      $stmt = $connection->prepare("INSERT INTO machines (machine, cost, years_depreciation) 
                                    VALUES(:machine, :cost, :years_depreciation)");
      $stmt->execute([
        'id_company' => $id_company,
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $dataMachine['cost'],
        'years_depreciation' => $dataMachine['years_depreciation'],
        //'minute_depreciation' => $dataMachine['minute_depreciation']
      ]);

      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = substr($e->getMessage(), 0, 15);

      if ($message == 'SQLSTATE[23000]')
        $message = 'Indicador de maquina ya registrada. Ingrese una nueva maquina';

      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updateMachinesByCompany($dataMachine)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("UPDATE machines SET machine = :machine, cost = :cost, 
                                    years_depreciation = :years_depreciation     
                                    WHERE id_machine = :id_machine");
      $stmt->execute([
        'id_machine' => $dataMachine['id_machine'],
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $dataMachine['cost'],
        'years_depreciation' => $dataMachine['years_depreciation'],
        //'minute_depreciation' => $dataMachine['minute_depreciation']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
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
