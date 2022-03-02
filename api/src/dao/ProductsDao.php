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

  public function insertProductByCompany($dataProduct, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataProduct['img'])) {
      try {
        $stmt = $connection->prepare("INSERT INTO products (reference, product, profitability) 
                                      VALUES(:reference, :product, :profitability)");
        $stmt->execute([
          'id_company' => $id_company,
          'reference' => $dataProduct['reference'],
          'product' => ucfirst(strtolower($dataProduct['product'])),
          'profitability' => $dataProduct['profitability']
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
      $stmt = $connection->prepare("INSERT INTO products (reference, product, profitability, img) 
                                      VALUES(:reference, :product, :profitability, :img)");
      $stmt->execute([
        'reference' => $dataProduct['reference'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'profitability' => $dataProduct['profitability'],
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    }
  }

  public function updateProductByCompany($dataProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataProduct['img'])) {
      try {
        $stmt = $connection->prepare("UPDATE products SET reference = :reference, product = :product, profitability = :profitability 
                                  WHERE id_product = :id_product");
        $stmt->execute([
          'id_product' => $dataProduct['id_product'],
          'reference' => $dataProduct['reference'],
          'product' => ucfirst(strtolower($dataProduct['product'])),
          'profitability' => $dataProduct['profitability']
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 2;
      } catch (\Exception $e) {
        $message = $e->getMessage();

        $error = array('info' => true, 'message' => $message);
        return $error;
      }
    } else {

      $stmt = $connection->prepare("UPDATE products SET reference = :reference, product = :product, profitability = :profitability, img = :img 
                                  WHERE id_product = :id_product");
      $stmt->execute([
        'id_product' => $dataProduct['id_product'],
        'reference' => $dataProduct['reference'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'profitability' => $dataProduct['profitability'],
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    }
  }

  public function deleteProduct($idProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM products WHERE id_product = :id_product");
    $stmt->execute(['id_product' => $idProduct]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM products WHERE id_product = :id_product");
      $stmt->execute(['id_product' => $idProduct]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function productsmaterials($idProduct)
  {

    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT m.id_material, m.reference, m.material, m.unit, m.cost 
                                  FROM products p INNER JOIN products_materials pm ON pm.id_product = p.id_product 
                                  INNER JOIN materials m ON m.id_material = pm.id_material 
                                  WHERE pm.id_product = :id_product AND pm.id_company = :id_company");
    $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
    $productsmaterials = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $productsmaterials));
    return $productsmaterials;
  }

  public function AddMaterialsProductByCompany($idProduct)
  {

    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT m.id_material, m.reference, m.material, m.unit, m.cost 
                                  FROM products p INNER JOIN products_materials pm ON pm.id_product = p.id_product 
                                  INNER JOIN materials m ON m.id_material = pm.id_material 
                                  WHERE pm.id_product = :id_product AND pm.id_company = :id_company");
    $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
    $productsmaterials = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $productsmaterials));
    return $productsmaterials;
  }

  public function productsprocess($idProduct)
  {

    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT p.id_product, p.reference, p.product, pp.id_product_process, pp.enlistment_time, pp.operation_time, mc.machine, pc.process
                                  FROM products p 
                                  LEFT JOIN products_process pp ON pp.id_product = p.id_product
                                  LEFT JOIN machines mc ON mc.id_machine = pp.id_machine 
                                  LEFT JOIN process pc ON pc.id_process = pp.id_process
                                  WHERE p.id_product = :id_product AND p.id_company = :id_company;");
    $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
    $productsprocess = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $productsprocess));
    return $productsprocess;
  }

  public function externalservices($idProduct)
  {

    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM services sx
                                  WHERE sx.id_product = :id_product AND sx.id_company = :id_company;");
    $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
    $externalservices = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("products", array('products' => $externalservices));
    return $externalservices;
  }
}
