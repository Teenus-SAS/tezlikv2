<?php


use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/AutoloaderSourceCode.php';

$app = AppFactory::create();
$app->setBasePath('/api');

require_once('../api/src/routes/routeExpenses.php');
require_once('../api/src/routes/routeFactoryLoad.php');
require_once('../api/src/routes/routeLogin.php');
require_once('../api/src/routes/routeMachines.php');
require_once('../api/src/routes/routeMaterials.php');
require_once('../api/src/routes/routePayroll.php');
require_once('../api/src/routes/routePrices.php');
require_once('../api/src/routes/routeProcess.php');
require_once('../api/src/routes/routeProducts.php');
require_once('../api/src/routes/routePuc.php');
require_once('../api/src/routes/routeUsers.php');


$app->run();
