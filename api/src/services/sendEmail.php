<?php

namespace tezlikv2\dao;
namespace tezlikv2\services;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class sendEmail
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function sendEmailRecoveryPassword($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM carga_fabril WHERE id_empresa = :id_company;");
    $stmt->execute(['id_company' => $id_company]);
    
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    
    $factoryloads = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("factory load", array('factory load' => $factoryloads));
    return $factoryloads;
  }

}