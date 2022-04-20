<?php

namespace tezlikv2\dao;

class UserInactiveTimeDao
{

  public function findSession()
  {
    @session_start();
    if (empty($_SESSION['active']) || time() - $_SESSION['time'] > 600) {
      session_destroy();
      echo "<script> window.location='http://tezlikv2/'; </script>";
      exit();
    } else
      @session_start();
  }
}
