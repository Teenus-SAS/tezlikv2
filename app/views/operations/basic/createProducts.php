
<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Productos</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Creación de Productos</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-4 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnNewProduct">Nuevo Producto</button>
                </div>
            </div>
            <!-- <div class="col-sm-4 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnCreateProduct">Carga masiva</button>
                </div>
            </div> -->
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateProduct">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formCreateProduct">
                            <div class="gridx4">
                                <label for="">Referencia</label>
                                <label for="">Nombre Producto</label>
                                <label for="">Rentabilidad(%)</label>
                                <label for=""></label>
                                <input type="text" class="form-control" name="idProduct" id="idProduct" hidden>
                                <input type="text" class="form-control" name="referenceProduct" id="referenceProduct">
                                <input type="text" class="form-control" name="product" id="product">
                                <input type="text" class="form-control text-center" name="profitability" id="profitability">
                                <button class="btn btn-primary" id="btnCreateProduct">Crear Producto</button>
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
                        <h5 class="card-title">Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tblProducts">

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

<script src="../../app/js/products/tblProducts.js"></script>
<script src="../../app/js/products/products.js"></script>