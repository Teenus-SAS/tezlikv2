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

    public function productsprocess($idProduct, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT p.id_product, p.reference, p.product, pp.id_process, pp.id_machine, pp.id_product_process,
                                                     pp.enlistment_time, pp.operation_time, mc.machine, pc.process
                                  FROM products p 
                                  LEFT JOIN products_process pp ON pp.id_product = p.id_product
                                  LEFT JOIN machines mc ON mc.id_machine = pp.id_machine 
                                  LEFT JOIN process pc ON pc.id_process = pp.id_process
                                  WHERE p.id_product = :id_product AND p.id_company = :id_company ORDER BY pp.id_machine ASC");
        $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
        $productsprocess = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("products", array('products' => $productsprocess));
        return $productsprocess;
    }

    // Consultar si existe el proceso del prodcuto en la BD
    public function findAExistingProductProcess($dataProductProcess)
    {
        $connection = Connection::getInstance()->getConnection();

        // Obtener id_producto
        $product = new ProductsDao();
        $findProduct = $product->findAExistingProduct($dataProductProcess['referenceProduct']);

        // Obtener id_proceso
        $stmt = $connection->prepare("SELECT id_process FROM process WHERE process = :process");
        $stmt->execute(['process' => $dataProductProcess['process']]);
        $findProcess = $stmt->fetch($connection::FETCH_ASSOC);

        // Obtener id_maquina
        $machine = new MachinesDao();
        $findMachine = $machine->findAExistingMachine($dataProductProcess['machine']);

        $stmt = $connection->prepare("SELECT id_product_process FROM products_process 
                                      WHERE id_product = :id_product AND id_process = :id_process AND id_machine = :id_machine");
        $stmt->execute([
            'id_product' => $findProduct['id_product'],
            'id_process' => $findProcess['id_process'],
            'id_machine' => $findMachine['id_machine']
        ]);
        $findProductProcess = $stmt->fetch($connection::FETCH_ASSOC);

        if ($findProductProcess == false) {
            $dataFindProductProcess = array_merge($findProduct, $findMachine, $findProcess);
            return $dataFindProductProcess;
        } else {
            $dataFindProductProcess = array_merge($findProductProcess, $findProduct, $findMachine, $findProcess);
            return $dataFindProductProcess;
        }
    }

    // Insertar productos procesos general
    public function generalInsertProductsProcess(
        $dataProductProcess,
        $idMachine,
        $idProduct,
        $id_company,
        $idProcess
    ) {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("INSERT INTO products_process (id_product, id_company, id_process, 
                                                          id_machine, enlistment_time, operation_time)
                                    VALUES (:id_product, :id_company, :id_process, :id_machine, :enlistment_time, :operation_time)");
            $stmt->execute([
                'id_product' => $idProduct,
                'id_company' => $id_company,
                'id_process' => $idProcess,
                'id_machine' => $idMachine,
                'enlistment_time' => $dataProductProcess['enlistmentTime'],
                'operation_time' => $dataProductProcess['operationTime']
            ]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    // Actualizar productos procesos general
    public function generalUpdateProductsProcess(
        $dataProductProcess,
        $idProductProcess,
        $idProduct,
        $idProcess,
        $idMachine
    ) {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE products_process SET id_product = :id_product, id_process = :id_process, id_machine = :id_machine, enlistment_time = :enlistment_time, operation_time = :operation_time
                                          WHERE id_product_process = :id_product_process");
            $stmt->execute([
                'id_product_process' => $idProductProcess,
                'id_product' => $idProduct,
                'id_process' => $idProcess,
                'id_machine' => $idMachine,
                'enlistment_time' => $dataProductProcess['enlistmentTime'],
                'operation_time' => $dataProductProcess['operationTime']
            ]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function insertProductsProcessByCompany($dataProductProcess, $id_company)
    {
        $this->generalInsertProductsProcess(
            $dataProductProcess,
            $dataProductProcess['idProduct'],
            $dataProductProcess['idProcess'],
            $dataProductProcess['idMachine'],
            $id_company
        );
    }

    public function updateProductsProcess($dataProductProcess)
    {
        $this->generalUpdateProductsProcess(
            $dataProductProcess,
            $dataProductProcess['idProductProcess'],
            $dataProductProcess['idProduct'],
            $dataProductProcess['idProcess'],
            $dataProductProcess['idMachine']
        );
    }

    // Insertar o Actualizar producto*proceso importado
    public function insertOrUpdateProductProcess($dataProductProcess, $id_company)
    {
        $dataFindProductProcess = $this->findAExistingProductProcess($dataProductProcess);

        if (empty($dataFindProductProcess['id_product_process'])) {
            // Insertar
            $this->generalInsertProductsProcess(
                $dataProductProcess,
                $dataFindProductProcess['id_machine'],
                $dataFindProductProcess['id_product'],
                $id_company,
                $dataFindProductProcess['id_process']
            );
        } else {
            // Actualizar
            $this->generalUpdateProductsProcess(
                $dataProductProcess,
                $dataFindProductProcess['id_product_process'],
                $dataFindProductProcess['id_machine'],
                $dataFindProductProcess['id_product'],
                $dataFindProductProcess['id_process']
            );
        }
    }

    public function deleteProductProcess($dataProductProcess)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM products_process WHERE id_product_process = :id_product_process");
        $stmt->execute(['id_product_process' => $dataProductProcess['idProductProcess']]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM products_process WHERE id_product_process = :id_product_process");
            $stmt->execute(['id_product_process' => $dataProductProcess['idProductProcess']]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
