<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Carga Fabril</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Asignación de costos directos relacionados a una máquina</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnNewFactoryLoad">Nueva Carga Fabril</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardFactoryLoad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="gridx5fl">
                            <label for="">Máquina</label>
                            <label for="">Descripción Carga fabril</label>
                            <label for="">Costo</label>
                            <label for="">Valor Minuto</label>
                            <label for=""></label>
                            <select class="form-control" name="maquine" id="maquine"></select>
                            <input class="form-control" name="descriptionFactoryLoad" id="descriptionFactoryLoad" />
                            <input class="form-control" name="cost" id="cost" />
                            <input class="form-control" name="valueMinute" id="valueMinute" disabled />
                            <button class="btn btn-primary" id="btnCreateFactory Load">Crear</button>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tblFactoryLoad">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div>
        </div>
    </div> -->

<script src="../../app/js/products/configProducts.js"></script>
<script src="../../app/js/services/tblFactoryLoad.js"></script>