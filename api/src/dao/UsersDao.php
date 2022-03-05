<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

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
    $rol = $_SESSION['rol'];

    $connection = Connection::getInstance()->getConnection();

    if ($rol == 1)
      $stmt = $connection->prepare("SELECT * FROM users WHERE rol = 2  ORDER BY firstname");
    else if ($rol == 4)
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

  /*OBTENER CANTIDAD DE USUARIOS CREADOS*/
  public function quantityUsers()
  {
    session_start();
    $quantityUsers = $_SESSION['quantityUsers'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT us.id_user, cl.quantity_users
                                  FROM users us
                                  INNER JOIN company_license cl ON cl.id_company = us.id_company
                                  WHERE cl.quantity_users = :quantity_users");
    $stmt->execute(['quantity_users' => $quantityUsers]);
    $user = $stmt->fetchAll($connection::FETCH_ASSOC);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("usuario Obtenido", array('usuario' => $user));
    return $user;
  }

  public function saveUser($dataUser)
  {
    $connection = Connection::getInstance()->getConnection();

    if (!empty($dataUser['id_user'])) {

      $stmt = $connection->prepare("SELECT * FROM users WHERE id_user = :id_user");
      $stmt->execute(['id_user' => $dataUser['id_user']]);
      $rows = $stmt->rowCount();

      if ($rows > 0) {
        if (!empty($dataUser['password'])) {
          $pass = password_hash($dataUser['password'], PASSWORD_DEFAULT);
          $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, pass = :pass, position = :position 
                                        WHERE id_user = id_user");
          $stmt->execute([
            'firstname' => ucwords(strtolower(trim($dataUser['names']))),
            'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
            'pass' => $pass,
            'position' => $dataUser['position'],
            'id_user' => $dataUser['id_user']
          ]);
        } else {
          $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, position = :position 
                                        WHERE id_user = :id_user");
          $stmt->execute([
            'firstname' => ucwords(strtolower(trim($dataUser['names']))),
            'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
            'position' => $dataUser['position'],
            'id_user' => $dataUser['id_user']
          ]);
        }
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        return 3;
      }
    } else {
      $pass = password_hash($dataUser['password'], PASSWORD_DEFAULT);
      $stmt = $connection->prepare("INSERT INTO users (firstname, lastname, email, pass, rol, position) 
                                    VALUES(:firstname, :lastname, :email, :pass, :rol, :position)");
      $stmt->execute([
        'firstname' => ucwords(strtolower(trim($dataUser['names']))),
        'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
        'email' => $dataUser['email'],
        'pass' => $pass,
        'rol' => $dataUser['rol'],
        'position' => $dataUser['position']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    }
  }

  public function updateUser($dataUser, $avatar, $cont)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $dataUser['email']]);
    $users = $stmt->fetch($connection::FETCH_ASSOC);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      if ($avatar == null) {
        $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, cellphone = :cellphone WHERE id_user = :id_user");
        $stmt->execute([
          'firstname' => ucwords(strtolower(trim($dataUser['names']))),
          'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
          'cellphone' => $dataUser['cellphone'],
          'id_user' => $users['id_user']
        ]);
      } else {
        if ($cont == 1) {
          $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, cellphone = :cellphone, avatar = :avatar WHERE id_user = :id_user");
          $stmt->execute([
            'firstname' => ucwords(strtolower(trim($dataUser['names']))),
            'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
            'cellphone' => $dataUser['cellphone'],
            'avatar' => $avatar,
            'id_user' => $users['id_user']
          ]);
        } else {
          $stmt = $connection->prepare("UPDATE users SET signature = :signature WHERE id_user = :id_user");
          $stmt->execute([
            'signature' => $avatar,
            'id_user' => $users['id_user']
          ]);
        }
      }

      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    }
  }

  public function deleteUser($dataUser)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users");
    $stmt->execute();
    $rows = $stmt->rowCount();

    if ($rows > 1) {
      $stmt = $connection->prepare("DELETE FROM users WHERE id_user = :id");
      $stmt->execute(['id' => $dataUser['idUser']]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function inactivateActivateUser($id_user)
  {

    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $users = $stmt->fetch($connection::FETCH_ASSOC);

    $users['status'] == 0 ? $status = 1 : $status = 0;

    $stmt = $connection->prepare("UPDATE users SET status = :statusUser WHERE id_user = :id_user");
    $stmt->execute([
      'id_user' => $id_user,
      'statusUser' => $status
    ]);
    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    return $status;
  }
}
