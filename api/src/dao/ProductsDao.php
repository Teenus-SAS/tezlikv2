<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ProductsDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllProductsByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM products WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $products = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $products));
    return $products;
  }

  public function InsertUpdateProductByCompany($dataProduct, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataProduct['id_product'])) {
      if (empty($dataProduct['img'])) {
        try {
          $stmt = $connection->prepare("INSERT INTO productos (empresas_id_empresa, ref, nombre, rentabilidad) 
                                      VALUES(:id_empresa, :referencia, :producto, :rentabilidad)");
          $stmt->execute([
            'id_empresa' => $id_company,
            'referencia' => $dataProduct['referenceProduct'],
            'producto' => ucfirst(strtolower($dataProduct['product'])),
            'rentabilidad' => $dataProduct['profitability'],
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
        $stmt = $connection->prepare("INSERT INTO productos (empresas_id_empresa, ref, nombre, rentabilidad, img) 
                                      VALUES(:id_empresa, :referencia, :producto, :rentabilidad, :img)");
        $stmt->execute([
          'id_empresa' => $id_company,
          'referencia' => $dataProduct['referenceProduct'],
          'producto' => ucfirst(strtolower($dataProduct['product'])),
          'rentabilidad' => $dataProduct['profitability'],
          'img' => $dataProduct['img']
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 1;
      }
    } else {
      if (empty($dataProduct['img'])) {
        $stmt = $connection->prepare("UPDATE productos SET ref = :referencia, nombre = :producto, rentabilidad = :rentabilidad 
                                    WHERE id_producto = :id_producto");
        $stmt->execute([
          'id_producto' => $dataProduct['id_product'],
          'referencia' => $dataProduct['referenceProduct'],
          'producto' => ucfirst(strtolower($dataProduct['product'])),
          'rentabilidad' => $dataProduct['profitability'],
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 2;
      } else {

        $stmt = $connection->prepare("UPDATE productos SET ref = :referencia, nombre = :producto, rentabilidad = :rentabilidad, img = :img 
                                    WHERE id_producto = :id_producto");
        $stmt->execute([
          'id_producto' => $dataProduct['id_product'],
          'referencia' => $dataProduct['referenceProduct'],
          'producto' => ucfirst(strtolower($dataProduct['product'])),
          'rentabilidad' => $dataProduct['profitability'],
          'img' => $dataProduct['img']
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 2;
      }
    }
  }

  public function deleteProduct($idProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM productos WHERE id_producto = :id_producto");
    $stmt->execute(['id_producto' => $idProduct]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM productos WHERE id_producto = :id_producto");
      $stmt->execute(['id_producto' => $idProduct]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function productshasmaterials($idProduct)
  {

    /* session_start(); */
    /* $id_company = $_SESSION['empresas_id_empresas']; */

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT m.id_materiales, m.referencia, m.descripcion, m.unidad, m.costo 
                                  FROM productos p INNER JOIN materiales_has_productos mhp ON mhp.productos_id_producto = p.id_producto 
                                  INNER JOIN materiales m ON m.id_materiales = mhp.materiales_id_materiales 
                                  WHERE mhp.productos_id_producto = :id_producto AND mhp.materiales_empresas_id_empresa = 44");
    $stmt->execute(['id_producto' => $idProduct]);
    $productshasmaterials = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $productshasmaterials));
    return $productshasmaterials;
  }

  public function productshasprocess($idProduct)
  {

    /* session_start(); */
    /* $id_company = $_SESSION['empresas_id_empresas']; */

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT p.id_producto, p.ref, p.nombre, tp.procesos_id_procesos, tp.tiempo_alistamiento, tp.tiempo_operacion, mq.nombre as maquina, pc.nombre as proceso 
                                  FROM productos p 
                                  LEFT JOIN tiempo_proceso tp ON tp.productos_id_producto = p.id_producto 
                                  LEFT JOIN maquinas mq ON mq.id_maquinas = tp.maquinas_id_maquinas 
                                  LEFT JOIN procesos pc ON pc.id_procesos = tp.procesos_id_procesos 
                                  WHERE p.id_producto = :id_producto AND p.empresas_id_empresa = 44;");
    $stmt->execute(['id_producto' => $idProduct]);
    $productshasprocess = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $productshasprocess));
    return $productshasprocess;
  }

  public function externalservices($idProduct)
  {

    /* session_start(); */
    /* $id_company = $_SESSION['empresas_id_empresas']; */

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM servicios_externos sx
                                  WHERE sx.id_producto = :id_producto AND sx.id_empresa = 44;");
    $stmt->execute(['id_producto' => $idProduct]);
    $externalservices = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $externalservices));
    return $externalservices;
  }
}
