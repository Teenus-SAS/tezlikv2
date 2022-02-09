<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PayrollDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllPayrollByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT n.id_nominas, n.empresas_id_empresa, n.cargo as nombre_empleado, n.salario, n.transporte, n.horas_extra, n.bonificacion, n.dotacion, n.dias_trabajo_mes, n.horas_dia, n.factor_prestacional, n.salario_neto, n.contrato, p.nombre as proceso 
                                  FROM nominas n INNER JOIN procesos p ON p.id_procesos = n.procesos_id_procesos 
                                  WHERE n.empresas_id_empresa = :id_company;");
    $stmt->execute(['id_company' => $id_company]);
    
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    
    $payroll = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("payroll", array('payroll' => $payroll));
    return $payroll;
  }

}
