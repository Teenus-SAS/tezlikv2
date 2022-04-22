<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class AutenticationUserDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findByEmail($dataUser)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $dataUser]);
    $user = $stmt->fetch($connection::FETCH_ASSOC);

    if ($user == false) {
      $stmt = $connection->prepare("SELECT * FROM users u WHERE email = :email");
      $stmt->execute(['email' => $dataUser]);
      $user = $stmt->fetch($connection::FETCH_ASSOC);
    }

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    $this->logger->notice("usuarios Obtenidos", array('usuarios' => $user));
    return $user;
  }

  /* Actualizar estado de sesion de Usuario */

  /* public function changeStatusUserLogin()
  {
    @session_start();
    $id_user = $_SESSION['idUser'];

    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT session_active FROM users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $session = $stmt->fetch($connection::FETCH_ASSOC);
    $session = $session['session_active'];

    ($session == 1 ? $session = 0 : $session == 0) ? $session = 1 : $session;

    $stmt = $connection->prepare("UPDATE users SET session_active = :session_active WHERE id_user = :id_user");
    $stmt->execute(['session_active' => $session, 'id_user' => $id_user]);
  } */
}
