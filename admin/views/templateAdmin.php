<?php

use tezlikv2\dao\UserInactiveTimeDao;

require_once(dirname(dirname(__DIR__)) . "/api/src/dao/app/login/UserInactiveTimeDao.php");
//include_once('../modals/modalNewSeller.php');
$userinactivetimeDao = new UserInactiveTimeDao();
$userinactivetimeDao->findSession();
?>

<!doctype html>
<html lang="es">

<head>
	<?php include_once(dirname(__DIR__) . '/partials/scriptsCSS.php'); ?>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">admin/
		<!--sidebar wrapper -->
		<?php //include_once(dirname(__DIR__) . '/partials/sidebar.php'); 
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
		<div class="overlay toggle-icon"></div>

		<!--Start Back To Top Button-->
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

		<?php include_once(dirname(__DIR__) . '/partials/footer.php'); ?>

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