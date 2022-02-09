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
    /* $id_company = $_SESSION['empresas_id_empresas']; */
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT gm.id_gastos_mensuales, p.ref, p.nombre as producto, gm.unidades_vendidas, gm.volumen_ventas, gm.gasto_asignable 
                                  FROM gastos_mensuales gm
                                  INNER JOIN	productos p ON p.id_producto = gm.productos_id_producto
                                  WHERE productos_empresas_id_empresa = :id_company;");
    $stmt->execute(['id_company' => 44]);
    
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    
    $process = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("process", array('process' => $process));
    return $process;
  }

}
