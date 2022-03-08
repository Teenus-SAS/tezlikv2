<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PucDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllCountsPUC()
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM puc ORDER BY id_puc ASC");
    $stmt->execute();

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $puc = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("process", array('process' => $puc));
    return $puc;
  }

  public function insertPuc($dataPuc)
  {
    $connection = Connection::getInstance()->getConnection();
    try {
      $stmt = $connection->prepare("INSERT INTO puc (number_count, count) VALUES (:number_count, :count)");
      $stmt->execute([
        'number_count' => $dataPuc['numberCount'],
        'count' => ucfirst(strtolower($dataPuc['count']))
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updatePuc($dataPuc)
  {
    $connection = Connection::getInstance()->getConnection();

    try {
      $stmt = $connection->prepare("UPDATE puc SET number_count = :number_count, count = :count
                                    WHERE id_puc = :id_puc");
      $stmt->execute([
        'id_puc' => $dataPuc['idPuc'],
        'number_count' => $dataPuc['numberCount'],
        'count' => ucfirst(strtolower($dataPuc['count']))
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deletePuc($id_puc)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM puc WHERE id_puc = :id_puc");
    $stmt->execute(['id_puc' => $id_puc]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM puc WHERE id_puc = :id_puc");
      $stmt->execute(['id_puc' => $id_puc]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
