<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ExternalServicesDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function externalServices($id_product)
    {
        session_start();
        $id_company = $_SESSION['id_company'];

        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM services sx
                                  WHERE sx.id_product = :id_product AND sx.id_company = :id_company;");
        $stmt->execute(['id_product' => $id_product, 'id_company' => $id_company]);
        $externalservices = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("products", array('products' => $externalservices));
        return $externalservices;
    }

    public function insertExternalServicesByCompany($dataExternalService, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("INSERT INTO services(service, cost, id_product, id_company)
                                          VALUES(:service, :cost, :id_product, :id_company)");
            $stmt->execute([
                'service' => ucfirst(strtolower($dataExternalService['service'])),
                'cost' => $dataExternalService['cost'],
                'id_product' => $dataExternalService['idProduct'],
                'id_company' => $id_company
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e->getCode() == 23000)
                $message = 'Servicio duplicado. Ingrese una nuevo servicio';
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function updateExternalServices($dataExternalService)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE services SET service=:service, cost=:cost, id_product=:id_product
                                          WHERE id_service = :id_service");
            $stmt->execute([
                'id_service' => $dataExternalService['idService'],
                'service' => ucfirst(strtolower($dataExternalService['service'])),
                'cost' => $dataExternalService['cost'],
                'id_product' => $dataExternalService['idProduct']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 2;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteExternalService($id_service)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM services WHERE id_service = :id_service");
        $stmt->execute(['id_service' => $id_service]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM services WHERE id_service = :id_service");
            $stmt->execute(['id_service' => $id_service]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
