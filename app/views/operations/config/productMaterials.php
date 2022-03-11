<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Productos</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Asignación de materias primas al producto</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnCreateProduct">Adicionar Nueva Materia Prima</button>
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

<div class="page-content-wrapper mt--45 mb-5 cardAddMaterials">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formAddMaterials" name="formAddMaterials">
                            <div class="gridx4pm">
                                <label for="">Materia Prima</label>
                                <label for="">Cantidad</label>
                                <label for="">Unidad</label>
                                <label for=""></label>
                                <select class="form-control" name="material" id="material"></select>
                                <input class="form-control" type="text" name="quantity" id="quantity">
                                <input class="form-control" type="text" name="unity" id="unity" disabled>
                                <button class="btn btn-primary" id="btnAddMaterials">Adicionar Materia Prima</button>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tblConfigMaterials" name="tblConfigMaterials">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../app/js/products/configProducts.js"></script>
<script src="../../app/js/rawMaterials/configRawMaterials.js"></script>
<script src="../../app/js/productMaterials/tblConfigMaterials.js"></script>
<script src="../../app/js/productMaterials/productMaterials.js"></script>