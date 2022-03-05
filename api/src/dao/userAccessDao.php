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
            $stmt = $connection->prepare("INSERT INTO users_access (id_user,create_product,create_materials,
                                                            create_machines,create_process,product_materials,product_process)
                                          VALUES (:id_user,:create_product,:create_materials,
                                                :create_machines,:create_process,:product_materials,:product_process)");
            $stmt->execute([
                'id_user' => $id_user,
                'create_product' => $dataUserAccess['createProduct'],
                'create_materials' => $dataUserAccess['createMaterials'],
                'create_machines' => $dataUserAccess['createMachines'],
                'create_process' => $dataUserAccess['createProcess'],
                'product_materials' => $dataUserAccess['productMaterials'],
                'product_process' => $dataUserAccess['productProcess']
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
            $stmt = $connection->prepare("UPDATE users_access SET id_user = :id_user, create_product = :create_product, create_materials = :create_materials,
                                                create_machines = :create_machines, create_process = :create_process, product_materials = :product_materials, product_process = :product_process
                                          WHERE id_user_access = :id_user_access");
            $stmt->execute([
                'id_user_access' => $dataUserAccess['idUserAccess'],
                'id_user' => $id_user,
                'create_product' => $dataUserAccess['createProduct'],
                'create_materials' => $dataUserAccess['createMaterials'],
                'create_machines' => $dataUserAccess['createMachines'],
                'create_process' => $dataUserAccess['createProcess'],
                'product_materials' => $dataUserAccess['productMaterials'],
                'product_process' => $dataUserAccess['productProcess']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
            return 2;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function deleteUserAccess($id_user_access)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users_access WHERE id_user_access = :id_user_access");
        $stmt->execute(['id_user_access' => $id_user_access]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM users_access WHERE id_user_access = :id_user_access");
            $stmt->execute(['id_user_access' => $id_user_access]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
