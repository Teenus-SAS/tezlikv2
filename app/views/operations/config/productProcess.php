<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Productos</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Asignación de procesos al producto</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-warning" id="btnCreateProcess">Nuevo Proceso</button>
                    <button class="btn btn-info ml-3" id="btnNewImportProductProcess">Importar Procesos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateRawMaterials">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="gridx2">
                            <label for="">Referencia</label>
                            <label for="">Producto</label>
                            <select class="form-control" name="refProduct" id="refProduct"></select>
                            <select class="form-control" name="selectNameProduct" id="selectNameProduct"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="page-content-wrapper mt--45 mb-5 cardAddProcess">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formAddProcess">
                            <div class="gridx6pp">
                                <label for="">Proceso</label>
                                <label for="">Maquina</label>
                                <label for="" class="text-center">t.alistamiento (min)</label>
                                <label for="" class="text-center">t.operacion (min)</label>
                                <label for="" class="text-center">t.total (min)</label>
                                <label for=""></label>
                                <select class="form-control" name="idProcess" id="idProcess"></select>
                                <select class="form-control" name="idMachine" id="idMachine"></select>
                                <input class="form-control text-center" type="number" name="enlistmentTime" id="enlistmentTime">
                                <input class="form-control text-center" type="number" name="operationTime" id="operationTime">
                                <input class="form-control text-center" type="text" name="totalTime" id="totalTime" disabled>
                                <button class="btn btn-success" id="btnAddProcess">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardImportProductsProcess">
    <div class="container-fluid">
        <div class="row">
            <form id="formImportProductProcess" enctype="multipart/form-data">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pt-3 pb-0">
                            <div class="gridx4ip">
                                <div class="form-group floating-label enable-floating-label show-label mt-3 drag-area" style="margin-top:0px!important">
                                    <input class="form-control" type="file" id="fileProductsProcess" accept=".xls,.xlsx">
                                    <label for="formFile" class="form-label"> Importar Productos*Procesos</label>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px;margin-top:7px">
                                    <button type="text" class="btn btn-success" id="btnImportProductsProcess">Importar</button>
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px;margin-top:7px">
                                    <button type="text" class="btn btn-info" id="btnDownloadFormatImportsProductsProcess">Descarga Formato</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                            <table class="table table-striped" id="tblConfigProcess" name="tblConfigProcess">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../app/js/products/configProducts.js"></script>
<script src="../../app/js/payroll/configProcessPayroll.js"></script>
<script src="../../app/js/machines/configMachines.js"></script>
<script src="../../app/js/productProcess/tblConfigProcess.js"></script>
<script src="../../app/js/productProcess/importProductProcess.js"></script>
<script src="../../app/js/productProcess/productProcess.js"></script>
<script src="../../app/js/import/import.js"></script>
<script src="../../app/js/import/file.js"></script>