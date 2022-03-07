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
        $stmt = $connection->prepare("INSERT INTO products(id_company, reference, product, profitability) 
                                      VALUES(:id_company, :reference, :product, :profitability)");
        $stmt->execute([
          'id_company' => $id_company,
          'reference' => $dataProduct['referenceProduct'],
          'product' => ucfirst(strtolower($dataProduct['product'])),
          'profitability' => $dataProduct['profitability']
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 1;
      } catch (\Exception $e) {
        $message = $e->getMessage();
        if ($e->getCode() == 23000)
          $message = 'Referencia duplicada. Ingrese una nueva referencia';
        $error = array('info' => true, 'message' => $message);
        return $error;
      }
    } else {
      $stmt = $connection->prepare("INSERT INTO products (reference, product, profitability, img) 
        VALUES(:reference, :product, :profitability, :img)");
      $stmt->execute([
        'id_company' => $id_company,
        'reference' => $dataProduct['referenceProduct'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'profitability' => $dataProduct['profitability'],
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    }
  }

  public function updateProduct($dataProduct)
  {
    $connection = Connection::getInstance()->getConnection();

    if (empty($dataProduct['img'])) {
      try {
        $stmt = $connection->prepare("UPDATE products SET reference = :reference, product = :product, profitability = :profitability 
                                    WHERE id_product = :id_product");
        $stmt->execute([
          'id_product' => $dataProduct['idProduct'],
          'reference' => $dataProduct['referenceProduct'],
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

      $stmt = $connection->prepare("UPDATE products SET ref = :reference, product = :product, profitability = :profitability, img = :img 
                                    WHERE id_product = :id_product");
      $stmt->execute([
        'id_product' => $dataProduct['idProduct'],
        'reference' => $dataProduct['referenceProduct'],
        'product' => ucfirst(strtolower($dataProduct['product'])),
        'profitability' => $dataProduct['profitability'],
        'img' => $dataProduct['img']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    }
  }

  public function deleteProduct($id_product)
  {
    session_start();
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM products WHERE id_product = :id_product");
    $stmt->execute(['id_product' => $id_product]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM products WHERE id_product = :id_product");
      $stmt->execute(['id_product' => $id_product]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
