<?php
session_start();

require_once 'controllers/templateController.php';
$template = new templateController();

/* $rol = $_SESSION['rol']; */

/* if ($rol == 1) */
    $template->ctrTemplateOperations();
/* else if ($rol == 2)
    $template->ctrTemplateAdmin();
 */