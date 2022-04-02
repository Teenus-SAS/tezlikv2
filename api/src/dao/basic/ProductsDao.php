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
    $stmt = $connection->prepare("SELECT p.id_product, p.reference, p.product, pc.profitability, pc.price, p.img 
                                  FROM products p 
                                  INNER JOIN products_costs pc ON p.id_product = pc.id_product
                                  WHERE p.id_company = :id_company");
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
        $stmt = $connection->prepare("INSERT INTO products(id_company, reference, product) 
                                      VALUES(:id_company, :reference, :product)");
        $stmt->execute([
          'id_company' => $id_company,
          'reference' => $dataProduct['referenceProduct'],
          'product' => ucfirst(strtolower($dataProduct['product']))
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      } catch (\Exception $e) {
        $message = $e->getMessage();
        if ($e->getCode() == 23000)
          $message = 'Referencia duplicada. Ingrese una nueva referencia';
        $error = array('info' => true, 'message' => $message);
        return $error;
      }
    } else {
      $stmt = $connection->prepare("INSERT INTO products (id_company, reference, product, img) 
        VALUES(:id_company, :reference, :product, :img)");
      $stmt->execute([
        'id_company' => $id_company,
        'reference' => $dataProduct['referenceProduct'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function updateProduct($dataProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataProduct['img'])) {
      try {
        $stmt = $connection->prepare("UPDATE products SET reference = :reference, product = :product 
                                    WHERE id_product = :id_product");
        $stmt->execute([
          'id_product' => $dataProduct['idProduct'],
          'reference' => $dataProduct['referenceProduct'],
          'product' => ucfirst(strtolower($dataProduct['product']))
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      } catch (\Exception $e) {
        $message = $e->getMessage();
        $error = array('info' => true, 'message' => $message);
        return $error;
      }
    } else {

      $stmt = $connection->prepare("UPDATE products SET ref = :reference, product = :product, img = :img 
                                    WHERE id_product = :id_product");
      $stmt->execute([
        'id_product' => $dataProduct['idProduct'],
        'reference' => $dataProduct['referenceProduct'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function deleteProduct($dataProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM products WHERE id_product = :id_product");
    $stmt->execute(['id_product' => $dataProduct['idProduct']]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM products WHERE id_product = :id_product");
      $stmt->execute(['id_product' => $dataProduct['idProduct']]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
