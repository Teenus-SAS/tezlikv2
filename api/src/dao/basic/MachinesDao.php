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

  /* Buscar si existe maquina en la BD */
  public function findAExistingMachine($nameMachine)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT id_machine FROM `machines` WHERE machine = :machine");
    $stmt->execute(['machine' => $nameMachine]);
    $findMachine = $stmt->fetch($connection::FETCH_ASSOC);

    if ($findMachine == false) {
      return 1;
    } else
      return $findMachine;
  }

  /* Insertar maquina general */
  public function generalInsertMachines($dataMachine, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $costMachine = str_replace('.', '', $dataMachine['cost']);
    $residualValue = str_replace('.', '', $dataMachine['residualValue']);

    try {
      $stmt = $connection->prepare("INSERT INTO machines (id_company ,machine, cost, years_depreciation, 
                                                residual_value, hours_machine, days_machine) 
                                    VALUES (:id_company ,:machine, :cost, :years_depreciation,
                                        :residual_value, :hours_machine, :days_machine)");
      $stmt->execute([
        'id_company' => $id_company,
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $costMachine,
        'years_depreciation' => $dataMachine['depreciationYears'],
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

  /* Actualizar maquina general */
  public function generalUpdateMachine($dataMachine, $idMachine)
  {
    $connection = Connection::getInstance()->getConnection();
    $costMachine = str_replace('.', '', $dataMachine['cost']);
    $residualValue = str_replace('.', '', $dataMachine['residualValue']);

    try {
      $stmt = $connection->prepare("UPDATE machines SET machine = :machine, cost = :cost, years_depreciation = :years_depreciation,
                                       residual_value = :residual_value , hours_machine = :hours_machine, days_machine = :days_machine   
                                    WHERE id_machine = :id_machine");
      $stmt->execute([
        'id_machine' => $idMachine,
        'machine' => ucfirst(strtolower($dataMachine['machine'])),
        'cost' => $costMachine,
        'years_depreciation' => $dataMachine['depreciationYears'],
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

  public function insertMachinesByCompany($dataMachine, $id_company)
  {
    $this->generalInsertMachines($dataMachine, $id_company);
  }

  public function updateMachine($dataMachine)
  {
    $this->generalUpdateMachine($dataMachine, $dataMachine['idMachine']);
  }

  /* Insertar o Actualizar Maquina importada */
  public function insertOrUpdateMachine($dataMachine, $id_company)
  {
    $findMachine = $this->findAExistingMachine($dataMachine['machine']);

    if ($findMachine == 1) {
      // Insert
      $this->generalInsertMachines($dataMachine, $id_company);
    } else
      // Update
      $this->generalUpdateMachine($dataMachine, $findMachine['id_machine']);
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
