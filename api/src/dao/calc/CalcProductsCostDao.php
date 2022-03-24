<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CalcProductsCostDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }


    public function calCostMaterialsProduct($dataProductMaterial, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        /* Suma todas las cantidades y costos de products_materials ingresados */
        $stmt = $connection->prepare("SELECT SUM(pm.quantity * m.cost) as cost 
                                        FROM products_materials pm 
                                        INNER JOIN materials m ON pm.id_material = m.id_material 
                                        WHERE pm.id_company = :id_company AND pm.id_product = :id_product");
        $stmt->execute(['id_company' => $id_company, 'id_product' => $dataProductMaterial['idProduct']]);
        $costMaterialsProduct = $stmt->fetch($connection::FETCH_ASSOC);

        /* Modificar costo total de products_costs */
        $stmt = $connection->prepare("UPDATE products_costs SET cost_materials = :materials
                                         WHERE id_product = :id_product AND id_company = :id_company");
        $stmt->execute([
            'materials' => $costMaterialsProduct['cost'],
            'id_product' => $dataProductMaterial['idProduct'],
            'id_company' => $id_company
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return false;
    }
}
