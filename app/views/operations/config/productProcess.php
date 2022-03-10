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
                    <button class="btn btn-primary" id="btnCreateProcess">Nuevo Proceso</button>
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
                        <from id="formAddProcess">
                            <div class="gridx6pp">
                                <label for="">Proceso</label>
                                <label for="">Maquina</label>
                                <label for="">t.alistamiento</label>
                                <label for="">t.operacion</label>
                                <label for="">t.total</label>
                                <label for=""></label>
                                <select class="form-control" name="idProcess" id="idProcess"></select>
                                <select class="form-control" name="idMachine" id="idMachine"></select>
                                <input class="form-control" type="number" name="enlistmentTime" id="enlistmentTime">
                                <input class="form-control" type="number" name="operationTime" id="operationTime">
                                <input class="form-control" type="text" name="totalTime" id="titalTime" disabled>
                                <button class="btn btn-primary" id="btnAddProcess">Adicionar</button>
                            </div>
                        </from>
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
                            <table class="table table-striped" id="tblConfigProcess">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../app/js/products/configProducts.js"></script>
<!-- <script src="../../app/js/products/tblConfigProcess.js"></script> -->
<script src="../../app/js/productProcess/tblConfigProcess.js"></script>
<script src="../../app/js/productProcess/productProcess.js"></script>