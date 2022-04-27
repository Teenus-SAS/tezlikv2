<?php

use tezlikv2\dao\UserInactiveTimeDao;

require_once(dirname(dirname(__DIR__)) . "/api/src/dao/login/UserInactiveTimeDao.php");
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
	<link rel="shortcut icon" href="../app/assets/images/favicon/favicon_tezlik.jpg" type="image/x-icon" />

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
									<h3 class="mb-1 font-weight-bold text-dark">Dashboard</h3>
									<ol class="breadcrumb mb-3 mb-md-0">
										<li class="breadcrumb-item active">Bienvenido</li>
									</ol>
								</div>
							</div>
							<!-- <div class="col-sm-7 col-xl-6">
								<form class="form-inline justify-content-sm-end">
									<div class="d-inline-flex mr-2 input-date input-date-sm">
										<input class="form-control form-control-sm" type="text" id="dashdaterange" placeholder="03-10-19 a 04-06-20">
										<div class="date-icon">
											<i class="bx bx-calendar fs-sm"></i>
										</div>
									</div>
									<div class="btn-group dropdown">
										<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
											<i class="bx bx-download mr-1"></i> Descargar <i class="bx bx-chevron-down"></i>
										</button>
										<div class="dropdown-menu-right dropdown-menu">
											<a href="javascript: void(0);" class="dropdown-item">
												<i class="bx bx-mail-send mr-1"></i> Email
											</a>
											<a href="javascript: void(0);" class="dropdown-item">
												<i class="bx bx-printer mr-1"></i> Imprimir
											</a>
											<a href="javascript: void(0);" class="dropdown-item">
												<i class="bx bx-file mr-1"></i> Re-Generate
											</a>
										</div>
									</div>
								</form>
							</div> -->
						</div>
					</div>
				</div>
				<!-- page content -->
				<div class="page-content-wrapper mt--45">
					<div class="container-fluid">
						<!-- Widget  -->
						<div class="row">
							<div class="col-md-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="media align-items-center">
											<div class="media-body">
												<span class="text-muted text-uppercase font-size-12 font-weight-bold">Productos</span>
												<h2 class="mb-0 mt-1" id="products"></h2>
											</div>
											<div class="text-center">
												<!-- <div id="t-rev"></div>
												<span class="text-success font-weight-bold font-size-13">
													<i class="bx bx-up-arrow-alt"></i> 10.21%
												</span> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="media align-items-center">
											<div class="media-body">
												<span class="text-muted text-uppercase font-size-12 font-weight-bold">Rentabilidad (Promedio)</span>
												<h2 class="mb-0 mt-1" id="profitabilityAverage"></h2>
											</div>
											<div class="text-center">
												<!-- <div id="t-order"></div>
												<span class="text-danger font-weight-bold font-size-13">
													<i class="bx bx-down-arrow-alt"></i> 5.05%
												</span> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="media align-items-center">
											<div class="media-body">
												<span class="text-muted text-uppercase font-size-12 font-weight-bold">Comisión de Ventas (Promedio)</span>
												<h2 class="mb-0 mt-1" id="comissionAverage"></h2>
											</div>
											<div class="text-center">
												<!-- <div id="t-user"></div>
												<span class="text-success font-weight-bold font-size-13">
													<i class="bx bx-up-arrow-alt"></i> 25.21%
												</span> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="media align-items-center">
											<div class="media-body">
												<span class="text-muted text-uppercase font-size-12 font-weight-bold">Gastos Generales</span>
												<h2 class="mb-0 mt-1" id="generalCost"></h2>
											</div>
											<div class="text-center">
												<!-- <div id="t-visitor"></div>
												<span class="text-danger font-weight-bold font-size-13">
													<i class="bx bx-down-arrow-alt"></i> 5.16%
												</span> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Row 2-->
						<div class="row align-items-stretch">
							<div class="col-md-4 col-lg-3">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Ventas</h5>
									</div>
									<div class="card-body p-0">
										<ul class="list-group list-group-flush">
											<li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Productos Vendidos</p>
														<h4 class="mb-0" id="productsSold"></h4>
													</div>
													<div class="avatar avatar-md bg-info mr-0 align-self-center">
														<i class="bx bx-layer fs-lg"></i>
													</div>
												</div>
											</li>
											<li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Ingresos por Ventas</p>
														<h4 class="mb-0" id="salesRevenue"></h4>
													</div>
													<div class="avatar avatar-md bg-primary mr-0 align-self-center">
														<i class="bx bx-bar-chart-alt fs-lg"></i>
													</div>
												</div>
											</li>
											<!-- <li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Product Sold</p>
														<h4 class="mb-0">8,235</h4>
													</div>
													<div class="avatar avatar-md bg-success mr-0 align-self-center">
														<i class="bx bx-chart fs-lg"></i>
													</div>
												</div>
											</li> -->
										</ul>
									</div>
								</div>
							</div>
							<!-- Begin total revenue chart -->
							<div class="col-md-4 col-lg-7" style="height: fit-content;">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Tiempos de Proceso por Producto</h5>
									</div>
									<div class="card-body pt-2">
										<!-- <div id="stats-chart"></div> -->
										<canvas id="chartTimeProcessProducts"></canvas>
									</div>
								</div>
							</div>

							<div class="col-md-4 col-lg-2">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Tiempos Promedio</h5>
									</div>
									<div class="card-body p-0">
										<ul class="list-group list-group-flush">
											<li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Alistamiento</p>
														<h4 class="mb-0 number" id="enlistmentTime"></h4>
													</div>
													<div class="avatar avatar-md bg-info mr-0 align-self-center">
														<i class="bx bx-layer fs-lg"></i>
													</div>
												</div>
											</li>
											<li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Operación</p>
														<h4 class="mb-0 number" id="operationTime"></h4>
													</div>
													<div class="avatar avatar-md bg-primary mr-0 align-self-center">
														<i class="bx bx-bar-chart-alt fs-lg"></i>
													</div>
												</div>
											</li>
											<!-- <li class="list-group-item py-4">
												<div class="media">
													<div class="media-body">
														<p class="text-muted mb-2">Product Sold</p>
														<h4 class="mb-0">8,235</h4>
													</div>
													<div class="avatar avatar-md bg-success mr-0 align-self-center">
														<i class="bx bx-chart fs-lg"></i>
													</div>
												</div>
											</li> -->
										</ul>
									</div>
								</div>
							</div>
							<!-- End total revenue chart -->
						</div>

						<div class="row">

							<div class="col-lg-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Costo Mano de Obra (Min)</h5>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="charWorkForceGeneral"></canvas>
											<div class="center-text">
												<p class="text-muted mb-1 font-weight-600">Total Costo </p>
												<h4 class="mb-0 font-weight-bold" id="totalCostWorkforce"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Costo Carga Fabril</h5>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="charFactoryLoadCost"></canvas>
											<div class="center-text">
												<p class="text-muted mb-1 font-weight-600">Tiempo Total</p>
												<h4 class="mb-0 font-weight-bold" id="factoryLoadCost"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- </div> -->

							<!-- <div class="row"> -->

							<div class="col-lg-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Gastos Generales</h5>
									</div>
									<div class="card-body pt-2">
										<div class="chart-container">
											<canvas id="charExpensesGenerals"></canvas>
											<div class="center-text">
												<p class="text-muted mb-1 font-weight-600">Total Gastos </p>
												<h4 class="mb-0 font-weight-bold" id="totalCost"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-9" style="height: fit-content;">
								<div class=" card">
									<div class="card-header">
										<h5 class="card-title">Costos Productos</h5>
									</div>
									<div class="card-body pt-2">
										<canvas id="charProductsCost"></canvas>
										<div class="center-text">
											<!-- <p class="text-muted mb-1 font-weight-600"></p> -->
											<!-- <h4 class="mb-0 font-weight-bold"></h4> -->
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Row 3-->
						<!-- <div class="row">
							<div class="col-12 col-lg-8">
								<div class="card">
									<div class="card-header dflex-between-center">
										<h5 class="card-title">Recent Orders</h5>
										<div class="export-fnc">
											<button class="btn btn-primary btn-sm mr-3 ml-1" data-effect="wave">
												<i class="bx bx-export"></i> Export
											</button>
											<div class="arrow-pagination">
												<ul class="pagination mb-0">
													<li class="page-item disabled"><a class="page-link" data-effect="wave" href="javascript:void(0)"><i class="bx bx-chevron-left"></i></a></li>
													<li class="page-item"><a class="page-link" data-effect="wave" href="javascript:void(0)"><i class="bx bx-chevron-right"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-hover table-nowrap mb-0">
												<thead>
													<tr>
														<th>#</th>
														<th>Product</th>
														<th>Customer</th>
														<th>Price</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>#1</td>
														<td>Bicycle</td>
														<td>Otto B</td>
														<td>$124</td>
														<td><span class="badge py-1 badge-soft-danger">Declined</span></td>
													</tr>
													<tr>
														<td>#2</td>
														<td>Addidas Shoes</td>
														<td>Danny Johnson</td>
														<td>$100</td>
														<td><span class="badge py-1 badge-soft-warning">Pending</span></td>
													</tr>
													<tr>
														<td>#3</td>
														<td>Cut Sleeve Jacket</td>
														<td>Alvin Newton</td>
														<td>$50</td>
														<td><span class="badge py-1 badge-soft-success">Delivered</span></td>
													</tr>
													<tr>
														<td>#4</td>
														<td>Half Shirt</td>
														<td>Bennie Perez</td>
														<td>$80</td>
														<td><span class="badge py-1 badge-soft-success">Delivered</span></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Quartly Sale</h5>
									</div>
									<div class="card-body pt-2">
										<div id="quartly-sale"></div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- Row 4-->
						<!-- <div class="row">
							<div class="col-lg-3">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Total Sales</h5>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<div class="chart">
												<canvas id="total-sale"></canvas>
											</div>
											<div class="center-text">
												<p class="text-muted mb-1 font-weight-600">Total Sale </p>
												<h4 class="mb-0 font-weight-bold">130</h4>
											</div>
										</div>
									</div>
								</div>
							</div> 

							<div class="col-lg-6">
								<div class="card">
									<div class="card-header dflex-between-center">
										<h5 class="card-title">Earning Statastics</h5>
										<div class="btn-group earningTabs">
											<button class="btn btn-primary btn-sm" data-effect="wave" data-type="weekly">
												Weekly
											</button>
											<button class="btn btn-outline-primary btn-sm" data-effect="wave" data-type="monthly">
												Monthly
											</button>
										</div>
									</div>
									<div class="card-body pt-2">
										<div id="sales-order"></div>
									</div>
								</div>
							</div> -

							<div class="col-lg-3">
								<div class="card revenue-card">
									<div class="card-header bg-info">
										<h5 class="card-title text-white">Revenue</h5>
									</div>
									<div class="card-body bg-info position-relative">
										<div class="chart-container">
											<div class="chart h-150">
												<canvas id="today-revenue"></canvas>
											</div>
										</div>
										<div class="center-text">
											<p class="text-light mb-1 font-weight-600">Sale </p>
											<h4 class="text-white mb-0 font-weight-bold">$600</h4>
										</div>
									</div>
									<div class="revenue-stats p-4">
										<div>
											<p class="text-muted">Target</p>
											<h4>$2000</h4>
										</div>
										<div>
											<p class="text-muted">Current</p>
											<h4>$1500</h4>
										</div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- Row 5 -->
						<!-- <div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Inventory Stock</h5>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-nowrap">
												<thead>
													<tr>
														<th>Serial</th>
														<th>Code</th>
														<th>Date</th>
														<th>Stock</th>
														<th>Stock Left</th>
														<th>Status</th>
														<th>Ratings</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>#1</td>
														<td><strong>8765482</strong></td>
														<td>November 14, 2019</td>
														<td>15000</td>
														<td>10000</td>
														<td><span class="badge badge-soft-success">In Stock</span></td>
														<td>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star-half text-warning"></i></a>
														</td>
													</tr>
													<tr>
														<td>#2</td>
														<td><strong>2366482</strong></td>
														<td>November 15, 2019</td>
														<td>15000</td>
														<td>100</td>
														<td><span class="badge badge-soft-danger">Out Stock</span></td>
														<td>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star-half text-warning"></i></a>
															<a href="#"><i class="bx bx-star text-warning"></i></a>
														</td>
													</tr>
													<tr>
														<td>#3</td>
														<td><strong>3557477</strong></td>
														<td>November 16, 2019</td>
														<td>15000</td>
														<td>7000</td>
														<td><span class="badge badge-soft-success">In Stock</span></td>
														<td>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
														</td>
													</tr>
													<tr>
														<td>#4</td>
														<td><strong>8747754</strong></td>
														<td>November 17, 2019</td>
														<td>15000</td>
														<td>8000</td>
														<td><span class="badge badge-soft-success">In Stock</span></td>
														<td>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star-half text-warning"></i></a>
															<a href="#"><i class="bx bx-star text-warning"></i></a>
															<a href="#"><i class="bx bx-star text-warning"></i></a>
														</td>
													</tr>
													<tr>
														<td>#5</td>
														<td><strong>9874745</strong></td>
														<td>November 18, 2019</td>
														<td>15000</td>
														<td>50</td>
														<td><span class="badge badge-soft-danger">Out Stock</span></td>
														<td>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star text-warning"></i></a>
															<a href="#"><i class="bx bxs-star-half text-warning"></i></a>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="my-3 d-flex justify-content-end">
											<ul class="pagination  flat-rounded-pagination">
												<li class="page-item disabled">
													<a href="javascript:void(0)" class="page-link" data-effect="wave" aria-label="Previous" tabindex="-1" aria-disabled="true">
														<i class="bx bx-chevron-left"></i>
													</a>
												</li>
												<li class="page-item active" aria-current="page">
													<a href="javascript:void(0)" class="page-link" data-effect="wave">1</a>
												</li>
												<li class="page-item" aria-current="page">
													<a href="javascript:void(0)" class="page-link" data-effect="wave">2</a>
												</li>
												<li class="page-item" aria-current="page">
													<a href="javascript:void(0)" class="page-link" data-effect="wave">3</a>
												</li>
												<li class="page-item">
													<a href="javascript:void(0)" class="page-link" data-effect="wave" aria-label="Next">
														<i class="bx bx-chevron-right"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
				<script src="../../app/js/dashboard/indicatorsGeneral.js"></script>
				<script src="../../app/js/dashboard/graphicsGeneral.js"></script>
			</div>
		</div>
		<!-- main content End -->

		<!-- footer -->
		<?php include_once  dirname(__DIR__) .  '/partials/footer.php'; ?>

		<!-- <div class="setting-sidebar">
			<div class="card mb-0">
				<div class="card-header">
					<h5 class="card-title dflex-between-center">
						Layouts
						<a href="javascript:void(0)"><i class="mdi mdi-close fs-sm"></i></a>
					</h5>
				</div>
				<div class="card-body">
					<div class="layout">
						<a href="index-horizontal.html">
							<img src="assets/images/horizontal.png" alt="Lettstart Admin" class="img-fluid" />
							<h6 class="font-size-16">Horizontal Layout</h6>
						</a>
					</div>
					<div class="layout">
						<a href="index.html">
							<img src="assets/images/vertical.png" alt="Lettstart Admin" class="img-fluid" />
							<h6 class="font-size-16">Vertical Layout</h6>
						</a>
					</div>
					<div class="layout">
						<a href="layout-dark-sidebar.html">
							<img src="assets/images/dark.png" alt="Lettstart Admin" class="img-fluid" />
							<h6 class="font-size-16">Dark Sidebar</h6>
						</a>
					</div>
				</div>
			</div>
		</div> -->
	</div>
	<!-- Page End -->

	<?php include_once dirname(__DIR__) . '/partials/scriptsJS.php'; ?>
	<script src="../../app/js/global/loadContent.js"></script>
	<script src="../../app/js/global/logout.js"></script>
	<script src="../../app/js/login/access.js"></script>
</body>

</html>