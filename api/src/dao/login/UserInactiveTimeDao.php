<?php

namespace tezlikv2\dao;

class UserInactiveTimeDao
{

  public function findSession()
  {
    @session_start();
    if (empty($_SESSION['active']) || time() - $_SESSION['time'] > 600) {
      /* Falta Metodo para cambiar estado de inicio de session */
      session_destroy();
      echo "<script> window.location='http://tezlikv2/'; </script>";
      exit();
    } else
      @session_start();
  }
}
