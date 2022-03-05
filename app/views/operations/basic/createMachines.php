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
                        <form id="formMachine">
                            <div class="gridx8">
                                <label for="">Nombre</label>
                                <label for="">Precio</label>
                                <label for="">Valor Residual</label>
                                <label for="">Años Depreciación</label>
                                <label for="">Horas de Trabajo</label>
                                <label for="">Dias de Trabajo</label>
                                <label for="">Depreciación x Min</label>
                                <label></label>
                                <input type="text" class="form-control" name="idMachine" id="idMachine" hidden>
                                <input type="text" class="form-control" name="machine" id="machine">
                                <input type="text" class="form-control money text-center" name="price" id="price">
                                <input type="text" class="form-control money text-center" name="residualValue" id="residualValue">
                                <input type="text" class="form-control number text-center" name="depreciationYears" id="depreciationYears">
                                <input type="number" class="form-control money text-center" name="hoursMachine" id="hoursMachine">
                                <input type="number" class="form-control money text-center" name="daysMachine" id="daysMachine">
                                <input type="text" class="form-control money text-center" name="depreciationMinute" id="depreciationMinute" disabled>
                                <button class="btn btn-primary" id="btnCreateMachine">Crear Máquina</button>
                            </div>
                        </form>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../app/js/machines/tblMachines.js"></script>
<script src="../../app/js/machines/machines.js"></script>
<script src="../../app/js/global/number.js"></script>