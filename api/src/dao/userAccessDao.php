<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class userAccessDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }
    public function findAllUsersAccess($id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $rol = $_SESSION['rol'];

        if ($rol == 2) {
            $stmt = $connection->prepare("SELECT usa.id_user, us.firstname, us.lastname, us.email, usa.create_product, usa.create_materials, usa.create_machines, usa.create_process, usa.product_materials, usa.product_process  
                                      FROM users_access usa 
                                      INNER JOIN users us ON us.id_user = usa.id_user
                                      WHERE us.id_company = :id_company;");
            $stmt->execute(['id_company' => $id_company]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            $users = $stmt->fetchAll($connection::FETCH_ASSOC);
            $this->logger->notice("usuarios Obtenidos", array('usuarios' => $users));
            return $users;
        }
    }

    public function findUserAccess($id_company, $id_user)
    {
        $connection = Connection::getInstance()->getConnection();
        $rol = $_SESSION['rol'];

        if ($rol == 2) {
            $stmt = $connection->prepare("SELECT usa.create_product, usa.create_materials, usa.create_machines, usa.create_process, usa.product_materials, usa.product_process  
                                      FROM users_access usa 
                                      INNER JOIN users us ON us.id_user = usa.id_user
                                      WHERE us.id_company = :id_company AND us.id_user = :id_user;");
            $stmt->execute(['id_company' => $id_company, 'id_user' => $id_user]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            $users = $stmt->fetchAll($connection::FETCH_ASSOC);
            $this->logger->notice("usuarios Obtenidos", array('usuarios' => $users));
            return $users;
        }
    }

    public function insertUserAccessByUsers($dataUserAccess, $id_user)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("INSERT INTO users_access (id_user,create_product,create_materials,create_machines,create_process
                                                            ,product_materials,product_process,factory_load,external_service,
                                                            product_line,payroll_load,expense,expense_distribution,user)
                                          VALUES (:id_user,:create_product,:create_materials,:create_machines,:create_process,
                                                :product_materials,:product_process,:factory_load,:external_service,
                                                :product_line,:payroll_load,:expense,:expense_distribution,:user)");
            $stmt->execute([
                'id_user' => $id_user,                                          'factory_load' => $dataUserAccess['factoryLoad'],
                'create_product' => $dataUserAccess['createProduct'],           'external_service' => $dataUserAccess['externalService'],
                'create_materials' => $dataUserAccess['createMaterials'],       'product_line' => $dataUserAccess['productLine'],
                'create_machines' => $dataUserAccess['createMachines'],         'payroll_load' => $dataUserAccess['payrollLoad'],
                'create_process' => $dataUserAccess['createProcess'],           'expense' => $dataUserAccess['expense'],
                'product_materials' => $dataUserAccess['productMaterials'],     'expense_distribution' => $dataUserAccess['expenseDistribution'],
                'product_process' => $dataUserAccess['productProcess'],         'user' => $dataUserAccess['user']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 1;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e->getCode() == 23000)
                $message = 'Usuario duplicado. Ingrese una nuevo usuario';
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function updateUserAccessByUsers($dataUserAccess, $id_user)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE users_access SET create_product = :create_product, create_materials = :create_materials, create_machines = :create_machines, create_process = :create_process, 
                                                        product_materials = :product_materials, product_process = :product_process, factory_load = :factory_load, external_service = :external_service,
                                                        product_line = :product_line, payroll_load = :payroll_load, expense = :expense, expense_distribution = :expense_distribution, user = :user
                                          WHERE id_user = :id_user");
            $stmt->execute([
                'id_user' => $id_user,                                          'factory_load' => $dataUserAccess['factoryLoad'],
                'create_product' => $dataUserAccess['createProduct'],           'external_service' => $dataUserAccess['externalService'],
                'create_materials' => $dataUserAccess['createMaterials'],       'product_line' => $dataUserAccess['productLine'],
                'create_machines' => $dataUserAccess['createMachines'],         'payroll_load' => $dataUserAccess['payrollLoad'],
                'create_process' => $dataUserAccess['createProcess'],           'expense' => $dataUserAccess['expense'],
                'product_materials' => $dataUserAccess['productMaterials'],     'expense_distribution' => $dataUserAccess['expenseDistribution'],
                'product_process' => $dataUserAccess['productProcess'],         'user' => $dataUserAccess['user']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 2;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteUserAccess($id_user)
    {

        session_start();
        $idUser = $_SESSION['idUser'];

        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users_access WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $id_user]);
        $user = $stmt->fetch($connection::FETCH_ASSOC);

        if ($user[$id_user] != $idUser) {

            $stmt = $connection->prepare("SELECT * FROM users_access WHERE id_user = :id_user");
            $stmt->execute(['id_user' => $id_user]);
            $rows = $stmt->rowCount();

            if ($rows > 0) {
                $stmt = $connection->prepare("DELETE FROM users_access WHERE id_user = :id_user");
                $stmt->execute(['id_user' => $id_user]);
                $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            }
        } else {
            return 1;
        }
    }
}
