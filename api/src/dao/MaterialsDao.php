<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class MaterialsDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllMaterialsByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM materials WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $materials = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("materials", array('materials' => $materials));
    return $materials;
  }

  public function InsertUpdateMaterialsByCompany($dataMaterials, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataMaterials['id_material'])) {
      try {
        $stmt = $connection->prepare("INSERT INTO materiales (empresas_id_empresa, referencia, descripcion, unidad, costo) 
                                      VALUES(:id_empresa, :referencia, :descripcion, :unidad, :costo)");
        $stmt->execute([
          'id_empresa' => $id_company,
          'referencia' => $dataMaterials['referenceProduct'],
          'producto' => ucfirst(strtolower($dataMaterials['product'])),
          'rentabilidad' => $dataMaterials['profitability'],
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 1;
      } catch (\Exception $e) {
        $message = substr($e->getMessage(), 0, 15);

        if ($message == 'SQLSTATE[23000]')
          $message = 'Referencia ya registrada. Ingrese una nueva referencia';

        $error = array('info' => true, 'message' => $message);
        return $error;
      }
    } else {

      $stmt = $connection->prepare("UPDATE productos SET ref = :referencia, nombre = :producto, rentabilidad = :rentabilidad 
                                    WHERE id_producto = :id_producto");
      $stmt->execute([
        'id_producto' => $dataMaterials['id_product'],
        'referencia' => $dataMaterials['referenceProduct'],
        'producto' => ucfirst(strtolower($dataMaterials['product'])),
        'rentabilidad' => $dataMaterials['profitability'],
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    }
  }
}
