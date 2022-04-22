<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class UserInactiveTimeDao
{

  public function findSession()
  {
    @session_start();
    if (empty($_SESSION['active']) || time() - $_SESSION['time'] > 600) {
      //$this->changeStatusUserLogin();

      session_destroy();
      echo "<script> window.location='http://tezlikv2/'; </script>";
      exit();
    } else
      @session_start();
  }

  /* Actualizar estado de sesion de Usuario */

  public function changeStatusUserLogin()
  {
    /* @session_start();
    $id_user = $_SESSION['idUser'];
    
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT session_active FROM users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $session = $stmt->fetch($connection::FETCH_ASSOC);
    $session = $session['session_active'];

    ($session == 1 ? $session = 0 : $session == 0) ? $session = 1 : $session;

    $stmt = $connection->prepare("UPDATE users SET session_active = :session_active WHERE id_user = :id_user");
    $stmt->execute(['session_active' => $session, 'id_user' => $id_user]); */
  }
}
