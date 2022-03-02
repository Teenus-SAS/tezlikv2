<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class autenticationDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findByEmail($Datauser)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $Datauser]);
    $user = $stmt->fetch($connection::FETCH_ASSOC);

    if ($user == false) {
      $stmt = $connection->prepare("SELECT * FROM users u WHERE email = :email");
      $stmt->execute(['email' => $Datauser]);
      $user = $stmt->fetch($connection::FETCH_ASSOC);
    }

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("usuarios Obtenidos", array('usuarios' => $user));
    return $user;
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

  public function findLicense($id_company)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT cl.license_end 
                                  FROM company_license cl WHERE cl.id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $licenseData = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("licenses get", array('licenses' => $licenseData));

    $today = date('Y-m-d');
    $licenseDay = $licenseData[0]['license_end'];
    $today < $licenseDay ? $license = 1 : $license = 0;
    return $license;
  }


  public function ChangePasswordUser($id_user, $newPass)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $pass = password_hash($newPass, PASSWORD_DEFAULT);

      $stmt = $connection->prepare("UPDATE users SET pass = :pass WHERE id_user = :id_user");
      $stmt->execute(['id_user' => $id_user, 'pass' => $pass]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function forgotPasswordUser($email)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {

      $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $longitudCadena = strlen($cadena);
      $new_pass = "";
      $longitudPass = 6;

      for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $new_pass .= substr($cadena, $pos, 1);
      }

      /* actualizar $pass en la DB */
      $pass = password_hash($new_pass, PASSWORD_DEFAULT);
      $stmt = $connection->prepare("UPDATE users SET pass = :pass WHERE email = :email");
      $stmt->execute(['email' => $email, 'pass' => $pass]);

      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

      /* Enviar $new_pass por email */
      return $new_pass;
    }
  }
}
