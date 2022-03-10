<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ProductsProcessDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function productsprocess($refProduct)
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
        $stmt->execute(['id_product' => $refProduct, 'id_company' => $id_company]);
        $productsprocess = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("products", array('products' => $productsprocess));
        return $productsprocess;
    }

    public function insertProductsProcessByCompany($dataProductProcess, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("INSERT INTO products_process (id_product, id_company, id_process, 
                                                          id_machine, enlistment_time, operation_time)
                                    VALUES (:id_product, :id_company, :id_process, :id_machine, :enlistment_time, :operation_time)");
            $stmt->execute([
                'id_product' => $dataProductProcess['refProduct'],
                'id_company' => $id_company,
                'id_process' => $dataProductProcess['idProcess'],
                'id_machine' => $dataProductProcess['idMachine'],
                'enlistment_time' => $dataProductProcess['enlistmentTime'],
                'operation_time' => $dataProductProcess['operationTime']
            ]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function updateProductsProcess($dataProductProcess)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE products_process SET id_product = :id_product, id_process = :id_process, id_machine = :id_machine, enlistment_time = :enlistment_time, operation_time = :operation_time
                                    WHERE id_product_process = :id_product_process");
            $stmt->execute([
                'id_product_process' => $dataProductProcess['idProductProcess'],
                'id_product' => $dataProductProcess['refProduct'],
                'id_process' => $dataProductProcess['idProcess'],
                'id_machine' => $dataProductProcess['idMachine'],
                'enlistment_time' => $dataProductProcess['enlistmentTime'],
                'operation_time' => $dataProductProcess['operationTime']
            ]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 2;
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteProductProcess($id_product_process)
    {
        session_start();
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM products_process WHERE id_product_process = :id_product_process");
        $stmt->execute(['id_product_process' => $id_product_process]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM products_process WHERE id_product_process = :id_product_process");
            $stmt->execute(['id_product_process' => $id_product_process]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
