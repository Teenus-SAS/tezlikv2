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
                    <button class="btn btn-primary" id="btnNewMachine" name="btnNewMachine">Crear Máquina</button>
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
                        <form id="formCreateMachine">
                            <div class="gridx4cm">
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <!-- <input type="text" class="form-control" name="idMachine" id="idMachine" hidden> -->
                                    <input type="text" class="form-control" name="machine" id="machine">
                                    <label for="">Nombre</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="text" class="form-control money text-center" name="cost" id="costMachine">
                                    <label for="">Precio</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="text" class="form-control money text-center" name="residualValue" id="residualValue">
                                    <label for="">Valor Residual</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="text" class="form-control number text-center" name="depreciationYears" id="depreciationYears">
                                    <label for="">Años Depreciación</label>
                                </div>
                            </div>
                            <div class="gridx4m mt-3">
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="number" class="form-control money text-center" name="hoursMachine" id="hoursMachine">
                                    <label for="">Horas de Trabajo</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="number" class="form-control money text-center" name="daysMachine" id="daysMachine">
                                    <label for="">Dias de Trabajo</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <input type="text" class="form-control money text-center" name="depreciationMinute" id="depreciationMinute">
                                    <label for="">Depreciación x Min</label>
                                </div>
                                <div style="margin-bottom:0px;margin-top:5px;">
                                    <button class="btn btn-primary" id="btnCreateMachine">Crear Máquina</button>
                                </div>

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