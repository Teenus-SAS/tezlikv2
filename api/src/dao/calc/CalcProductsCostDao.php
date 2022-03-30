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


    public function calcCostMaterialsProduct($dataProductMaterial, $id_company)
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
    }

    /* Al modificar materia prima */
    public function calcCostMaterialsByRawMaterials($dataMaterials, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT id_product AS idProduct FROM products_materials WHERE id_material =:id_material AND id_company = :id_company");
        $stmt->execute(['id_material' => $dataMaterials['idMaterial'], 'id_company' => $id_company]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);

        /* Suma todas las cantidades y costos de products_materials ingresados */

        for ($i = 0; $i < sizeof($dataProduct); $i++) {
            $stmt = $connection->prepare("SELECT SUM(pm.quantity * m.cost) as cost 
                                          FROM products_materials pm 
                                          INNER JOIN materials m ON pm.id_material = m.id_material 
                                          WHERE pm.id_company = :id_company AND pm.id_product = :id_product");
            $stmt->execute(['id_company' => $id_company, 'id_product' => $dataProduct[$i]['idProduct']]);
            $costMaterialsProduct = $stmt->fetch($connection::FETCH_ASSOC);

            /* Modificar costo total de products_costs */
            $stmt = $connection->prepare("UPDATE products_costs SET cost_materials = :materials
                                         WHERE id_product = :id_product AND id_company = :id_company");
            $stmt->execute([
                'materials' => $costMaterialsProduct['cost'],
                'id_product' => $dataProduct[$i]['idProduct'],
                'id_company' => $id_company
            ]);
        }

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }


    public function calcCostPayroll($dataProductProcess, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        /* Sumar tiempo total por valor por minuto */
        $stmt = $connection->prepare("SELECT SUM(p.minute_value * (pp.enlistment_time + pp.operation_time)) AS costPayroll
                                        FROM products_process pp 
                                        INNER JOIN payroll p ON p.id_process = pp.id_process 
                                        WHERE pp.id_product = :id_product AND pp.id_company = :id_company");
        $stmt->execute([
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);
        $payroll = $stmt->fetch($connection::FETCH_ASSOC);

        /* Modificar costo de nomina de products_costs */
        $stmt = $connection->prepare("UPDATE products_costs SET cost_workforce = :workforce
                                        WHERE id_product = :id_product AND id_company = :id_company");
        $stmt->execute([
            'workforce' => $payroll['costPayroll'],
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }

    /* Al modificar la nomina */
    public function calcCostPayrollByPayroll($dataPayroll, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        // Obtener idProduct atravez de nomina
        $stmt = $connection->prepare("SELECT pp.id_product as idProduct
                                      FROM products_process pp
                                      WHERE pp.id_process = :id_process AND pp.id_company = :id_company");
        $stmt->execute(['id_process' => $dataPayroll['idProcess'], 'id_company' => $id_company]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);

        for ($i = 0; $i < sizeof($dataProduct); $i++) {
            // Sumar tiempo total por valor por minuto
            $stmt = $connection->prepare("SELECT SUM(p.minute_value * (pp.enlistment_time + pp.operation_time)) AS costPayroll
                                        FROM products_process pp 
                                        INNER JOIN payroll p ON p.id_process = pp.id_process 
                                        WHERE pp.id_product = :id_product AND pp.id_company = :id_company");
            $stmt->execute([
                'id_product' => $dataProduct[$i]['idProduct'],
                'id_company' => $id_company
            ]);
            $payroll = $stmt->fetch($connection::FETCH_ASSOC);

            // Modificar costo de nomina de products_costs

            $stmt = $connection->prepare("UPDATE products_costs SET cost_workforce = :workforce
                                        WHERE id_product = :id_product AND id_company = :id_company");
            $stmt->execute([
                'workforce' => $payroll['costPayroll'],
                'id_product' => $dataProduct[$i]['idProduct'],
                'id_company' => $id_company
            ]);
        }

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }

    public function calcCostIndirectCost($dataProductProcess, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        // Buscar el producto asociado a la maquina modificada
        $stmt = $connection->prepare("SELECT id_product_process, id_machine 
                                      FROM products_process 
                                      WHERE id_product = :id_product");
        $stmt->execute(['id_product' => $dataProductProcess['idProduct']]);
        $dataTableProductsProcess = $stmt->fetchAll($connection::FETCH_ASSOC);

        $indirectCost = 0;

        for ($i = 0; $i < sizeof($dataTableProductsProcess); $i++) {

            // Suma aparte del cost_minute de la carga fabril
            $stmt = $connection->prepare("SELECT SUM(cost_minute) as totalCostMinute 
                                            FROM manufacturing_load WHERE id_machine = :id_machine");
            $stmt->execute(['id_machine' => $dataTableProductsProcess[$i]['id_machine']]);
            $dataCostManufacturingLoad = $stmt->fetch($connection::FETCH_ASSOC);

            // Captura de depreciacion por minuto y tiempo total de costo indirecto por materia
            $stmt = $connection->prepare("SELECT pp.id_machine, m.minute_depreciation, (pp.enlistment_time + pp.operation_time) AS totalTime
                                            FROM products_process pp
                                            INNER JOIN machines m ON m.id_machine = pp.id_machine
                                            WHERE pp.id_machine = :id_machine AND pp.id_product = :id_product
                                            AND pp.id_product_process = :id_product_process");
            $stmt->execute([
                'id_machine' => $dataTableProductsProcess[$i]['id_machine'],
                'id_product' => $dataProductProcess['idProduct'],
                'id_product_process' => $dataTableProductsProcess[$i]['id_product_process']
            ]);
            $productProcessIndirectCost = $stmt->fetch($connection::FETCH_ASSOC);

            // Calculo costo indirecto
            $processMachineindirectCost = ($dataCostManufacturingLoad['totalCostMinute'] + $productProcessIndirectCost['minute_depreciation']) * $productProcessIndirectCost['totalTime'];

            $indirectCost = $indirectCost + $processMachineindirectCost;
        }

        /* Modificar costo indirecto de products_costs */
        $stmt = $connection->prepare("UPDATE products_costs SET cost_indirect_cost = :cost_indirect_cost
                                        WHERE id_product = :id_product AND id_company = :id_company");
        $stmt->execute([
            'cost_indirect_cost' => $indirectCost,
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }

    /* Al modificar la maquina */
    public function calcCostIndirectCostByMachine($dataMachine, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        // Buscar todos los productos que registren el id de la maquina
        $stmt = $connection->prepare("SELECT id_product AS idProduct 
                                      FROM products_process
                                      WHERE id_machine = :id_machine AND id_company = :id_company");
        $stmt->execute(['id_machine' => $dataMachine['idMachine'], 'id_company' => $id_company]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);


        for ($i = 0; $i < sizeof($dataProduct); $i++) {

            // Buscar el producto asociado a la maquina modificada
            $stmt = $connection->prepare("SELECT id_machine
                                          FROM products_process 
                                          WHERE id_product = :id_product");
            $stmt->execute(['id_product' => $dataProduct[$i]['idProduct']]);
            $dataProductMachine = $stmt->fetchAll($connection::FETCH_ASSOC);

            $indirectCost = 0;

            for ($j = 0; $j < sizeof($dataProductMachine); $j++) {
                /* Calcula la carga fabril por maquina y producto */
                $stmt = $connection->prepare("SELECT pp.id_machine, SUM(ml.cost_minute) AS costMinuteLoadManufacturing, m.minute_depreciation, pp.enlistment_time, pp.operation_time, (pp.enlistment_time + pp.operation_time) AS totalTimeProcess, ((m.minute_depreciation + SUM(ml.cost_minute))* (pp.enlistment_time + pp.operation_time)) AS indirectCost
                                              FROM manufacturing_load ml
                                              INNER JOIN machines m ON ml.id_machine = m.id_machine
                                              INNER JOIN products_process pp ON pp.id_machine = ml.id_machine
                                              WHERE m.id_machine = :id_machine AND pp.id_product = :id_product;");
                $stmt->execute([
                    'id_machine' => $dataProductMachine[$j]['id_machine'],
                    'id_product' => $dataProduct[$i]['idProduct']
                ]);

                $processMachineindirectCost = $stmt->fetch($connection::FETCH_ASSOC);
                $indirectCost = $indirectCost + $processMachineindirectCost['indirectCost'];
            }

            // Modificar costo indirecto de products_costs
            $stmt = $connection->prepare("UPDATE products_costs SET cost_indirect_cost = :cost_indirect_cost
                                            WHERE id_product = :id_product AND id_company = :id_company");
            $stmt->execute([
                'cost_indirect_cost' => $indirectCost,
                'id_product' => $dataProduct[$i]['idProduct'],
                'id_company' => $id_company
            ]);
        }

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }

    /* Al modificar la carga fabril */
    public function calcCostIndirectCostByFactoryLoad($dataFactoryLoad, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        // Buscar todos los productos que registren el id de la carga fabril
        $stmt = $connection->prepare("SELECT id_product AS idProduct 
                                      FROM products_process
                                      WHERE id_machine = :id_machine AND id_company = :id_company");
        $stmt->execute(['id_machine' => $dataFactoryLoad['idMachine'], 'id_company' => $id_company]);
        $dataProduct = $stmt->fetchAll($connection::FETCH_ASSOC);


        for ($i = 0; $i < sizeof($dataProduct); $i++) {

            // Buscar el producto asociado a la maquina modificada
            $stmt = $connection->prepare("SELECT id_machine
                                          FROM products_process 
                                          WHERE id_product = :id_product");
            $stmt->execute(['id_product' => $dataProduct[$i]['idProduct']]);
            $dataProductMachine = $stmt->fetchAll($connection::FETCH_ASSOC);

            $indirectCost = 0;

            for ($j = 0; $j < sizeof($dataProductMachine); $j++) {
                /* Calcula la carga fabril por maquina y producto */
                $stmt = $connection->prepare("SELECT pp.id_machine, SUM(ml.cost_minute) AS costMinuteLoadManufacturing, m.minute_depreciation, pp.enlistment_time, pp.operation_time, (pp.enlistment_time + pp.operation_time) AS totalTimeProcess, ((m.minute_depreciation + SUM(ml.cost_minute))* (pp.enlistment_time + pp.operation_time)) AS indirectCost
                                              FROM manufacturing_load ml
                                              INNER JOIN machines m ON ml.id_machine = m.id_machine
                                              INNER JOIN products_process pp ON pp.id_machine = ml.id_machine
                                              WHERE m.id_machine = :id_machine AND pp.id_product = :id_product;");
                $stmt->execute([
                    'id_machine' => $dataProductMachine[$j]['id_machine'],
                    'id_product' => $dataProduct[$i]['idProduct']
                ]);

                $processMachineindirectCost = $stmt->fetch($connection::FETCH_ASSOC);
                $indirectCost = $indirectCost + $processMachineindirectCost['indirectCost'];
            }

            // Modificar costo indirecto de products_costs
            $stmt = $connection->prepare("UPDATE products_costs SET cost_indirect_cost = :cost_indirect_cost
                                            WHERE id_product = :id_product AND id_company = :id_company");
            $stmt->execute([
                'cost_indirect_cost' => $indirectCost,
                'id_product' => $dataProduct[$i]['idProduct'],
                'id_company' => $id_company
            ]);
        }

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
}
