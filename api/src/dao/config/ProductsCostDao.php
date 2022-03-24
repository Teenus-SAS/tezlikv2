<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ProductsCostDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    /* Falta la funcion de consultar */

    public function insertProductsCostByCompany($dataProduct, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        /* Obtener id_product agregado */

        $stmt = $connection->prepare("SELECT MAX(id_product) AS idProduct FROM products WHERE id_company = :id_company");
        $stmt->execute(['id_company' => $id_company]);
        $product = $stmt->fetch($connection::FETCH_ASSOC);

        $stmt = $connection->prepare("INSERT INTO products_costs(id_product, id_company, profitability) 
                                        VALUES (:id_product, :id_company, :profitability)");
        //commision_sale
        $stmt->execute([
            'id_product' => $product['idProduct'],
            'id_company' => $id_company,
            'profitability' => $dataProduct['profitability']
            // 'commision_sale' => $dataProduct['commisionSale']

        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 1;
    }

    public function updateProductsCost($dataProduct)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("UPDATE products_costs SET profitability = :profitability
                                      WHERE id_product = :id_product");
        // commision_sale = :commision_sale
        $stmt->execute([
            'profitability' => $dataProduct['profitability'],
            'id_product' => $dataProduct['idProduct']
            // 'commision_sale' => $dataProduct['commisionSale']
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 2;
    }

    public function deleteProductsCost($dataProduct)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM products_costs WHERE id_product = :id_product");
        $stmt->execute(['id_product' => $dataProduct['idProduct']]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM products_costs WHERE id_product = :id_product");
            $stmt->execute(['id_product' => $dataProduct['idProduct']]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
