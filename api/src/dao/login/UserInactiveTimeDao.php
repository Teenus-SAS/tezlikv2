<?php

namespace tezlikv2\dao;

class UserInactiveTimeDao
{

  public function findSession()
  {
    @session_start();
    if (empty($_SESSION['active']) || time() - $_SESSION['time'] > 600) {
      session_destroy();
      //header('location: ../../../');
    } else
      @session_start();
  }
}
