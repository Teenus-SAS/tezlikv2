<!-- <body class="horizontal-navbar"> -->
<!-- Begin Page -->
<!-- <div class="page-wrapper"> -->
<!-- Begin Header -->

<!-- Begin main content -->
<!-- <div class="main-content"> -->
<!-- content -->
<!--  <div class="page-content"> -->
<!-- page header -->
<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Máquinas</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Creación de Máquinas</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnCreateMachine">Crear Máquina</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateMachines">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Crear Máquina</h5>
                    </div>
                    <div class="card-body">
                        <div class="gridx3">
                            <label for="">Nombre</label>
                            <label for="">Precio</label>
                            <label for="">Valor Residual</label>
                            <label for="">Años de Depreciación</label>
                            <label for="">Depreciación por Minuto</label>
                            <input type="text" class="form-control" id="nameMachine">
                            <input type="text" class="form-control" id="priceMachine">
                            <input type="text" class="form-control text-center" id="depreciationMachine">
                            <input type="text" class="form-control text-center" id="depreciationMinute">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- page content -->
<div class="page-content-wrapper mt--45">
    <div class="container-fluid">
        <!-- Row 5 -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Máquinas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tblMachines">

                            </table>
                        </div>
                        <!-- <div class="my-3 d-flex justify-content-end">
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
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div>
        </div>
    </div> -->

<script src="../../app/js/machines/tblMachines.js"></script>
<script src="../../app/js/machines/machines.js"></script>