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

  /* Consultar si existe materia prima en la BD */
  public function findAExistingRawMaterial($referenceMaterial)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT id_material FROM materials WHERE reference = :reference");
    $stmt->execute(['reference' => $referenceMaterial]);
    $findMaterial = $stmt->fetch($connection::FETCH_ASSOC);

    if ($findMaterial == false) {
      return 1;
    } else
      return $findMaterial;
  }

  /* Insert General */
  public function generalInsertRawMaterial($dataMaterial, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $costRawMaterial = str_replace('.', '', $dataMaterial['costRawMaterial']);

    try {
      $stmt = $connection->prepare("INSERT INTO materials (id_company ,reference, material, unit, cost) 
                                      VALUES(:id_company ,:reference, :material, :unit, :cost)");
      $stmt->execute([
        'id_company' => $id_company,
        'reference' => $dataMaterial['refRawMaterial'],
        'material' => ucfirst(strtolower($dataMaterial['nameRawMaterial'])),
        'unit' => $dataMaterial['unityRawMaterial'],
        'cost' => $costRawMaterial
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

  /* Update General */
  public function generalUpdateRawMaterial($dataMaterial, $idMaterial)
  {
    $connection = Connection::getInstance()->getConnection();
    $costRawMaterial = str_replace('.', '', $dataMaterial['costRawMaterial']);

    try {
      $stmt = $connection->prepare("UPDATE materials SET reference = :reference, material = :material, unit = :unit, cost = :cost 
                                    WHERE id_material = :id_material");
      $stmt->execute([
        'id_material' => $idMaterial,
        'reference' => $dataMaterial['refRawMaterial'],
        'material' => ucfirst(strtolower($dataMaterial['nameRawMaterial'])),
        'unit' => $dataMaterial['unityRawMaterial'],
        'cost' => $costRawMaterial
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function insertMaterialsByCompany($dataMaterial, $id_company)
  {
    $this->generalInsertRawMaterial($dataMaterial, $id_company);
  }

  public function updateMaterialsByCompany($dataMaterial)
  {
    $this->generalUpdateRawMaterial($dataMaterial, $dataMaterial['idMaterial']);
  }

  /* Insertar o Actualizar materia prima importada */
  public function insertOrUpdateRawMaterial($dataMaterial, $id_company)
  {
    $findMaterial = $this->findAExistingRawMaterial($dataMaterial['refRawMaterial']);

    if ($findMaterial == 1) {
      // insertar
      $this->generalInsertRawMaterial($dataMaterial, $id_company);
    } else
      // actualizar
      $this->generalUpdateRawMaterial($dataMaterial, $findMaterial['id_material']);
  }

  public function deleteMaterial($dataMaterial)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM materials WHERE id_material = :id_material");
    $stmt->execute(['id_material' => $dataMaterial['idMaterial']]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM materials WHERE id_material = :id_material");
      $stmt->execute(['id_material' => $dataMaterial['idMaterial']]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
