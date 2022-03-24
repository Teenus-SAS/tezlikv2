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
        return false;
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
        return false;
    }

    public function calcCostIndirectCost($dataProductProcess, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        /* Sumar costo por minuto de nomina */
        $stmt = $connection->prepare("SELECT SUM(ml.cost_minute) as costMinute
                                        FROM manufacturing_load ml
                                        LEFT JOIN products_process pp ON pp.id_machine = ml.id_machine
                                        WHERE pp.id_product = :id_product AND pp.id_company = :id_company");
        $stmt->execute([
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);
        $dataCostMinute = $stmt->fetch($connection::FETCH_ASSOC);

        /* Sumar el resto de columnas */

        $stmt = $connection->prepare("SELECT SUM(m.minute_depreciation) as totalMinuteDepreciation, 
                                                SUM(pp.enlistment_time + pp.operation_time) as totalTime
                                        FROM products_process pp
                                        LEFT JOIN machines m ON m.id_machine = pp.id_machine
                                        WHERE pp.id_product = :id_product AND pp.id_company = :id_company");
        $stmt->execute([
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);
        $dataIndirectCost = $stmt->fetch($connection::FETCH_ASSOC);

        /* Calcular costos indirectos de products_costs */
        $indirectCost = ($dataCostMinute['costMinute'] + $dataIndirectCost['totalMinuteDepreciation']) * ($dataIndirectCost['totalTime']);

        /* Modificar costo indirecto de products_costs */
        $stmt = $connection->prepare("UPDATE products_costs SET cost_indirect_cost = :cost_indirect_cost
                                        WHERE id_product = :id_product AND id_company = :id_company");
        $stmt->execute([
            'cost_indirect_cost' => $indirectCost,
            'id_product' => $dataProductProcess['idProduct'],
            'id_company' => $id_company
        ]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return false;
    }
}
