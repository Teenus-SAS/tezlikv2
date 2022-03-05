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

  /*Obtener cantidad para creacion de usuario permitidos*/

  public function quantityUsersAllows()
  {
    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT quantity_user FROM company_license 
                                  WHERE id_company = :id_company");
    $stmt->execute(['id_company' => $id_company]);
    $quantity_users = $stmt->fetch($connection::FETCH_ASSOC);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("usuario Obtenido", array('usuario' => $quantity_users));
    return $quantity_users;
  }

  /*Obtener cantidad de usuarios creados*/

  public function quantityUsersCreated()
  {
    session_start();
    $id_company = $_SESSION['id_company'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT COUNT(*) FROM `users` WHERE id_company = id_company;");
    $stmt->execute(['id_company' => $id_company]);
    $quantity_users = $stmt->fetch($connection::FETCH_ASSOC);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("cantidad usuarios obtenidos", array('cantidad usuarios' => $quantity_users));
    return $quantity_users;
  }

  public function saveUser($dataUser)
  {
    $connection = Connection::getInstance()->getConnection();
    $newPass == $this->NewPassUser();

    $pass = password_hash($newPass, PASSWORD_DEFAULT);
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


    /* Enviar email al usuario creado */

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    return 2;
  }

  public function updateUser($dataUser, $avatar)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $dataUser['email']]);
    $users = $stmt->fetch($connection::FETCH_ASSOC);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      if ($avatar == null) {
        $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, active = :active
                                      WHERE id_user = :id_user");
        $stmt->execute([
          'firstname' => ucwords(strtolower(trim($dataUser['names']))),
          'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
          'active' => 1,
          'id_user' => $users['id_user'],
        ]);
      } else {

        $stmt = $connection->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, avatar = :avatar, active = :active
                                        WHERE id_user = :id_user");
        $stmt->execute([
          'firstname' => ucwords(strtolower(trim($dataUser['names']))),
          'lastname' => ucwords(strtolower(trim($dataUser['lastnames']))),
          'cellphone' => $dataUser['cellphone'],
          'avatar' => $avatar,
          'active' => 1,
          'id_user' => $users['id_user']
        ]);
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


  public function NewPassUser()
  {
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $longitudCadena = strlen($cadena);
    $new_pass = "";
    $longitudPass = 6;

    for ($i = 1; $i <= $longitudPass; $i++) {
      $pos = rand(0, $longitudCadena - 1);
      $new_pass .= substr($cadena, $pos, 1);
    }

    /* Enviar $new_pass */
    return $new_pass;
  }
}
