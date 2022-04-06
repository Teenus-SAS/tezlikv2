<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PriceProductDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    // Calcular precio General
    public function calcPrice($idProduct)
    {
        $connection = Connection::getInstance()->getConnection();

        // Calcular precio del producto
        $stmt = $connection->prepare("SELECT
                                        ((pc.cost_workforce+ pc.cost_materials + pc.cost_indirect_cost + ed.assignable_expense)/((100-pc.commision_sale-pc.profitability)/100)) as totalPrice 
                                      FROM products_costs pc
                                      INNER JOIN expenses_distribution ed ON ed.id_product = pc.id_product
                                      WHERE pc.id_product = :id_product");
        $stmt->execute(['id_product' => $idProduct]);
        $dataPrice = $stmt->fetch($connection::FETCH_ASSOC);

        // Modificar 
        $stmt = $connection->prepare("UPDATE products_costs SET price = :price WHERE id_product = :id_product");
        $stmt->execute([
            'price' => $dataPrice['totalPrice'],
            'id_product' => $idProduct
        ]);
    }

    // Calcular precio por Maquina
    public function calcPriceByMachine($idMachine)
    {
        $connection = Connection::getInstance()->getConnection();

        // Consultar productos relacionados con la maquina
        $stmt = $connection->prepare("SELECT id_product FROM products_process WHERE id_machine = :id_machine");
        $stmt->execute(['id_machine' => $idMachine]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);

        for ($i = 0; $i < sizeof($dataProduct); $i++) {
            // Calcular precio del producto
            $stmt = $connection->prepare("SELECT pc.id_product,
                                                ((pc.cost_workforce+ pc.cost_materials + pc.cost_indirect_cost + ed.assignable_expense)/((100-pc.commision_sale-pc.profitability)/100)) as totalPrice 
                                          FROM products_costs pc
                                          INNER JOIN expenses_distribution ed ON ed.id_product = pc.id_product
                                          WHERE pc.id_product = :id_product");
            $stmt->execute(['id_product' => $dataProduct[$i]['id_product']]);
            $dataPrice = $stmt->fetch($connection::FETCH_ASSOC);

            if ($dataPrice == false) {
                // No existe el producto asociado a la tabla products_cost";
            } else {
                // Modificar 
                $stmt = $connection->prepare("UPDATE products_costs SET price = :price WHERE id_product = :id_product");
                $stmt->execute([
                    'price' => $dataPrice['totalPrice'],
                    'id_product' => $dataProduct[$i]['id_product']
                ]);
            }
        }
    }

    // Calcular precio por Materia Prima
    public function calcPriceByMaterial($idMaterial)
    {
        $connection = Connection::getInstance()->getConnection();

        // Consultar productos relacionados con la maquina
        $stmt = $connection->prepare("SELECT id_product FROM products_materials WHERE id_material = :id_material");
        $stmt->execute(['id_material' => $idMaterial]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);

        if (empty($dataProduct)) {
            // No hay ningun producto asociado a esa materia prima
        } else {
            for ($i = 0; $i < sizeof($dataProduct); $i++) {
                // Calcular precio del producto
                $stmt = $connection->prepare("SELECT pc.id_product,
                                                ((pc.cost_workforce+ pc.cost_materials + pc.cost_indirect_cost + ed.assignable_expense)/((100-pc.commision_sale-pc.profitability)/100)) as totalPrice 
                                          FROM products_costs pc
                                          INNER JOIN expenses_distribution ed ON ed.id_product = pc.id_product
                                          WHERE pc.id_product = :id_product");
                $stmt->execute(['id_product' => $dataProduct[$i]['id_product']]);
                $dataPrice = $stmt->fetch($connection::FETCH_ASSOC);

                // Modificar 
                $stmt = $connection->prepare("UPDATE products_costs SET price = :price WHERE id_product = :id_product");
                $stmt->execute([
                    'price' => $dataPrice['totalPrice'],
                    'id_product' => $dataProduct[$i]['id_product']
                ]);
            }
        }
    }

    // Calcular precio por Nomina
    public function calcPriceByPayroll($idProcess)
    {
        $connection = Connection::getInstance()->getConnection();

        // Consultar productos relacionados con la maquina
        $stmt = $connection->prepare("SELECT id_product FROM products_process WHERE id_process = :id_process");
        $stmt->execute(['id_process' => $idProcess]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);


        for ($i = 0; $i < sizeof($dataProduct); $i++) {
            // Calcular precio del producto
            $stmt = $connection->prepare("SELECT pc.id_product,
                                                ((pc.cost_workforce+ pc.cost_materials + pc.cost_indirect_cost + ed.assignable_expense)/((100-pc.commision_sale-pc.profitability)/100)) as totalPrice 
                                          FROM products_costs pc
                                          INNER JOIN expenses_distribution ed ON ed.id_product = pc.id_product
                                          WHERE pc.id_product = :id_product");
            $stmt->execute(['id_product' => $dataProduct[$i]['id_product']]);
            $dataPrice = $stmt->fetch($connection::FETCH_ASSOC);

            if ($dataPrice == false) {
                // No existe el producto asociado a la tabla products_cost";
            } else {
                // Modificar 
                $stmt = $connection->prepare("UPDATE products_costs SET price = :price WHERE id_product = :id_product");
                $stmt->execute([
                    'price' => $dataPrice['totalPrice'],
                    'id_product' => $dataProduct[$i]['id_product']
                ]);
            }
        }
    }
}
