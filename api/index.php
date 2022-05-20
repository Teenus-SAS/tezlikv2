<?php


use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/AutoloaderSourceCode.php';

$app = AppFactory::create();
$app->setBasePath('/api');

/* App */

// Analysis
require_once('../api/src/routes/analysis/routeReviewRawMaterials.php');

// Basic
require_once('../api/src/routes/basic/routeMachines.php');
require_once('../api/src/routes/basic/routeMaterials.php');
require_once('../api/src/routes/basic/routeProcess.php');
require_once('../api/src/routes/basic/routeProducts.php');

// Config
require_once('../api/src/routes/config/routeExternalServices.php');
require_once('../api/src/routes/config/routeFactoryLoad.php');
require_once('../api/src/routes/config/routeProductsMaterials.php');
require_once('../api/src/routes/config/routeProductsProcess.php');

// Dashboard
require_once('../api/src/routes/dashboard/routeDashboardGenerals.php');
require_once('../api/src/routes/dashboard/routeDashboardProducts.php');

// Double factor
require_once('../api/src/routes/doubleFactor/routeDoubleFactor.php');

// General
require_once('../api/src/routes/general/routeExpenses.php');
require_once('../api/src/routes/general/routeExpensesDistribution.php');
require_once('../api/src/routes/general/routePayroll.php');
require_once('../api/src/routes/general/routeProcessPayroll.php');

// Global
require_once('../api/src/routes/global/routeCompany.php');
require_once('../api/src/routes/global/routePuc.php');

// Login
require_once('../api/src/routes/login/routeLogin.php');
require_once('../api/src/routes/login/routeLicenseCompany.php');
require_once('../api/src/routes/login/routepassUser.php');

// Prices
require_once('../api/src/routes/prices/routePrices.php');

// Tools
require_once('../api/src/routes/tools/routeSupport.php');

// User
require_once('../api/src/routes/users/routeUserAccess.php');
require_once('../api/src/routes/users/routeUsers.php');
require_once('../api/src/routes/users/routeQuantityUsers.php');
require_once('../api/src/routes/users/routeUsersStatus.php');


$app->run();
