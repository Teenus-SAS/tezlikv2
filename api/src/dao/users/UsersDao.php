<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

$quantityUsersDao = new QuantityUsersDao();

class UsersDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAll()
  {
    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();

    if ($id_company == 1)
      $stmt = $connection->prepare("SELECT * FROM users WHERE id_company = 2  ORDER BY firstname");
    else if ($id_company == 4)
      $stmt = $connection->prepare("SELECT * FROM users ORDER BY firstname");

    $stmt->execute();
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $users = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("usuarios Obtenidos", array('usuarios' => $users));
    return $users;
  }

  public function findAllUsersByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM users  WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $users = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("users", array('users' => $users));
    return $users;
  }

  public function findUser()
  {
    session_start();
    $email = $_SESSION['email'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM users u WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetchAll($connection::FETCH_ASSOC);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("usuario Obtenido", array('usuario' => $user));
    return $user;
  }

  public function saveUser($dataUser, $id_company)
  {
    $newPassDao = new NewPassUserDao();
    $connection = Connection::getInstance()->getConnection();
    $newPass = $newPassDao->NewPassUser();
    $pass = password_hash($newPass, PASSWORD_DEFAULT);
    
    $stmt = $connection->prepare("INSERT INTO users (firstname, lastname, email, password, id_company, active) 
                                    VALUES(:firstname, :lastname, :email, :pass, :id_company, :active)");
    $stmt->execute([
      'firstname' => ucwords(strtolower(trim($dataUser['names']))),
      'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
      'email' => trim($dataUser['email']),
      'pass' => $pass,
      'id_company' => $id_company,
      'active' => 1
    ]);



    /* Enviar email al usuario creado */

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    return 2;
  }

  public function updateUser($dataUser, $pathAvatar)
  {
    $connection = Connection::getInstance()->getConnection();

    // $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    // $stmt->execute(['email' => $dataUser['email']]);
    // $users = $stmt->fetch($connection::FETCH_ASSOC);
    // $rows = $stmt->rowCount();
    if ($pathAvatar == null) {
      $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, active = :active
                                      WHERE id_user = :id_user");
      $stmt->execute([
        'firstname' => ucwords(strtolower(trim($dataUser['names']))),
        'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
        'active' => 1,
        'id_user' => $dataUser['idUser'],
      ]);
    } else {

      $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, avatar = :avatar, active = :active
                                        WHERE id_user = :id_user");
      $stmt->execute([
        'firstname' => ucwords(strtolower(trim($dataUser['names']))),
        'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
        'avatar' => $pathAvatar,
        'active' => 1,
        'id_user' => $dataUser['idUser']
      ]);
    }

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    return 1;
  }


  public function deleteUser($idUser)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users");
    $stmt->execute();
    $rows = $stmt->rowCount();

    if ($rows > 1) {
      $stmt = $connection->prepare("DELETE FROM users WHERE id_user = :id");
      $stmt->execute(['id' => $idUser]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
