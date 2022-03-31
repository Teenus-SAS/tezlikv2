<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CostWorkforceDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
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
}
