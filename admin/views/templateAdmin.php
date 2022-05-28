<?php

use tezlikv2\dao\UserInactiveTimeDao;

require_once(dirname(dirname(__DIR__)) . "/api/src/dao/app/login/UserInactiveTimeDao.php");
//include_once('../modals/modalNewSeller.php');
$userinactivetimeDao = new UserInactiveTimeDao();
$userinactivetimeDao->findSession();
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="LetStart Admin is a full featured, multipurpose, premium bootstrap admin template built with Bootstrap 4 Framework, HTML5, CSS and JQuery.">
	<meta name="keywords" content="admin, panels, dashboard, admin panel, multipurpose, bootstrap, bootstrap4, all type of dashboards">
	<meta name="author" content="MatrrDigital">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Tezlik | Dashboard</title>
	<link rel="shortcut icon" href="/assets/images/favicon/favicon_tezlik.jpg" type="image/x-icon" />

	<?php include_once dirname(__DIR__) . '/partials/scriptsCSS.php'; ?>
</head>


<body class="horizontal-navbar">
	<!-- Begin Page -->
	<div class="page-wrapper">
		<!-- Begin Header -->
		<?php include_once dirname(__DIR__) . '/partials/header.php'; ?>

		<!-- Begin Left Navigation -->
		<?php include_once dirname(__DIR__) . '/partials/nav.php'; ?>

		<!-- Begin main content -->
		<div class="main-content">
			<!-- content -->
			<div class="page-content">
				<!-- page header -->
				<div class="page-title-box">
					<div class="container-fluid">
						<div class="row align-items-center">
							<div class="col-sm-5 col-xl-6">
								<div class="page-title">
									<h3 class="mb-1 font-weight-bold text-dark">Dashboard Administrador</h3>
									<ol class="breadcrumb mb-3 mb-md-0">
										<li class="breadcrumb-item active">Bienvenido</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- page content -->

				<script src="/app/js/dashboard/indicatorsGeneral.js"></script>
				<script src="/app/js/dashboard/graphicsGeneral.js"></script>
			</div>
		</div>
		<!-- main content End -->

		<!-- footer -->
		<?php include_once  dirname(__DIR__) . '/partials/footer.php'; ?>

	</div>
	<!-- Page End -->

	<?php include_once dirname(__DIR__) . '/partials/scriptsJS.php'; ?>
	<script src="/app/js/global/loadContent.js"></script>
	<script src="/app/js/global/logout.js"></script>
	<script src="/app/js/login/access.js"></script>

</body>


<body>
	<!--wrapper-->
	<div class="wrapper">admin/
		<!--sidebar wrapper -->
		<?php //include_once(dirname(__DIR__).'/partials/sidebar.php');
		?>

		<!--start header -->
		<?php include_once(dirname(__DIR__) . '/partials/header.php'); ?>

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<?php if ($rol == 4) { ?>
					<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
						<div class="breadcrumb-title pe-3">Administrador</div>
						<div class="ps-3">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb mb-0 p-0">
									<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">Usuarios</li>
								</ol>
							</nav>
						</div>
						<div class="ms-auto">
							<div class="btn-group">
								<button type="button" class="btn btn-primary" id="createUser" data-bs-toggle="modal" data-bs-target="#modalCreateSeller">Crear Nuevo Usuario</button>
							</div>
						</div>
					</div>

					<hr />
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table id="tableUsers" class="table table-striped table-bordered" style="width:100%">

								</table>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<!--start overlay-->
		<!-- <div class="overlay toggle-icon"></div> -->

		<!--Start Back To Top Button-->
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>


	</div>

	<!--start switcher-->
	<!-- <?php include_once(dirname(__DIR__) . '/partials/darkmode.php'); ?> -->


	<!-- Bootstrap JS -->
	<?php include_once(dirname(__DIR__) . '/partials/scriptsJS.php'); ?>
	<script>
		tipo = "<?= $_SESSION['rol'] ?>"
	</script>
	<script src="/admin/js/global/validation.js"></script>
	<script src="/admin/js/users/users.js"></script>
	<script src="/admin/js/users/rols.js"></script>
	<script src="/admin/js/global/logout.js"></script>
	<script src="/admin/js/global/profile.js"></script>

	<script src="/admin/js/global/loadContent.js"></script>
	<!-- <script src="/admin/js/login/access.js"></script> -->

</body>

</html>