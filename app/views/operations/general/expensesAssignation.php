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
                    <h3 class="mb-1 font-weight-bold text-dark">Gastos Generales</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Asignación de Gastos Generales</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-primary" id="btnNewExpense">Nuevo Gasto</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateExpenses">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formCreateExpenses">
                            <div class="gridx3">
                                <label for="">Cuenta</label>
                                <label for="">Valor</label>
                                <label for=""></label>
                                <select class="form-control" name="idPuc" id="idPuc"></select>
                                <input type="text" class="form-control number text-center" id="expenseValue" name="expenseValue">
                                <button class="btn btn-primary" id="btnCreateExpense">Crear Gasto</button>
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
                            <table class="table table-striped" id="tblExpenses">

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


<script src="../../app/js/global/number.js"></script>
<script src="../../app/js/expenses/expense.js"></script>
<script src="../../app/js/expenses/tblExpenses.js"></script>
<script src="../../app/js/puc/configPuc.js"></script>