<?php

namespace tezlikv2\dao;

use DateTime;
use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class adminDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    //OBTENER CANTIDAD DE USUARIOS GENERALES ACTIVOS
    //OBTENER CANTIDAD DE USUARIOS PERMITIDOS POR EMPRESA
    //ACTUALIZAR CANTIDAD DE USUARIOS PERMITIDOS POR EMPRESA
    //OBTENER TODAS LAS EMPRESAS
    //OBTENER DATOS EMPRESA Y LICENCIA
    //OBTENER DIAS QUE QUEDAN PARA TERMINAR LA LICENCIA
    //CANTIDAD DE PRODUCTOS GENERAL
    //CANTIDAD TOTAL DE PRODUCTOS POR EMPRESA
    //OBTENER PUC TOTALES
    //ACTUALIZAR PUC

    //**************************************************************** */

    //OBTENER CANTIDAD DE USUARIOS GENERALES ACTIVOS
    public function usersStatus($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT COUNT(active)AS quantity FROM users WHERE active = 1");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $active = $stmt->fetch($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $active));

        return $active;
    }


    //OBTENER CANTIDAD DE USUARIOS PERMITIDOS POR EMPRESA
    public function usersAllowed($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cp.company, cl.quantity_user FROM companies cp 
                                      INNER JOIN companies_licenses cl ON cp.id_company = cl.id_company");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $allowedData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $allowedData));

        return $allowedData;
    }


    //ACTUALIZAR CANTIDAD DE USUARIOS PERMITIDOS POR EMPRESA
    public function updateUsersAllowed($quantity_user, $id_company_license)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("UPDATE companies_licenses SET quantity_user = :quantity_user
                                      WHERE id_company_license = :id_company_license");
        $stmt->execute([
            'id_company_license' => $id_company_license,
            'quantity_user' => $quantity_user
        ]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }


    //OBTENER TODAS LAS EMPRESAS
    public function findCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM companies");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $companyData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $companyData));

        return $companyData;
    }


    //OBTENER DATOS EMPRESA Y LICENCIA
    public function findCompanyLicense($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cp.company, cp.nit, cp.state, cp.city, cp.country, cp.address,
                                      cp.telephone, cl.license_start, cl.license_end, cl.quantity_user, cl.status
                                      FROM companies cp INNER JOIN companies_licenses cl ON cp.id_company = cl.id_company");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $companyData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $companyData));

        return $companyData;
    }


    //OBTENER DIAS QUE QUEDAN PARA TERMINAR LA LICENCIA
    public function findLicenseDays($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT cl.license_end FROM companies_licenses cl WHERE cl.id_company = :id_company;");
        $stmt->execute(['id_company' => $id_company]);
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $licenseData = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $licenseData));

        $today  = new DateTime(date('Y-m-d'));
        $licenseEnd = new DateTime($licenseData[0]['license_end']);
        $days = $today->diff($licenseEnd);

        return $days->days;
    }


    //CANTIDAD DE PRODUCTOS GENERAL
    public function totalProducts($id_company)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT COUNT(id_product) AS quantity FROM products");
        $stmt->execute();
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        $quantityUsers = $stmt->fetch($connection::FETCH_ASSOC);
        $this->logger->notice("licenses get", array('licenses' => $quantityUsers));

        return $quantityUsers;
    }


    //CANTIDAD TOTAL DE PRODUCTOS POR EMPRESA
    public function totalProductsByCompany($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT p.id_product, p.reference, p.product, pc.profitability, pc.commission_sale, pc.price, p.img 
                                  FROM products p 
                                  INNER JOIN products_costs pc ON p.id_product = pc.id_product
                                  WHERE p.id_company = :id_company");
        $stmt->execute(['id_company' => $id_company]);

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $products = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("products", array('products' => $products));
        return $products;
    }


    //OBTENER PUC TOTALES
    public function findAllCountsPUC()
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM puc ORDER BY id_puc ASC");
        $stmt->execute();

        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        $puc = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("process", array('process' => $puc));
        return $puc;
    }


    //ACTUALIZAR PUC
    public function updateCountsPUC($dataPuc)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE puc SET number_count = :number_count, count = :count
                                          WHERE id_puc = :id_puc");
            $stmt->execute([
                'id_puc' => trim($dataPuc['idPuc']),
                'number_count' => trim($dataPuc['numberCount']),
                'count' => ucfirst(strtolower(trim($dataPuc['count'])))
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }
}
