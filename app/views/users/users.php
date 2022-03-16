<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-5 col-xl-6">
                <div class="page-title">
                    <h3 class="mb-1 font-weight-bold text-dark">Usuarios y Accesos</h3>
                    <ol class="breadcrumb mb-3 mb-md-0">
                        <li class="breadcrumb-item active">Creación de Usuario y Configuración de accesos</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-7 col-xl-6">
                <div class="form-inline justify-content-sm-end">
                    <button class="btn btn-warning" id="btnNewUser">Nuevo Usuario y Accesos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateUsers">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formCreateUser">
                            <div class="gridx3estandar">
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <label for="">Nombres </label>
                                    <input type="text" class="form-control" id="nameUser" name="nameUser">
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <label for="">Apellidos </label>
                                    <input type="text" class="form-control" id="lastnameUser" name="lastnameUser">
                                </div>
                                <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px">
                                    <label for="">Email </label>
                                    <input type="text" class="form-control" id="emailUser" name="emailUser">
                                </div>
                                <!-- <div class="form-group floating-label enable-floating-label show-label" style="margin-bottom:0px;margin-top:4px">
                                    <button class="btn btn-success" id="btnCreateUser">Crear Usuario</button>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content-wrapper mt--45 mb-5 cardCreateAccessUser">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formCreateAccessUser">
                            <div class="gridx13">
                                <!-- <div class="form-group floating-label enable-floating-label show-label namesUserAccess">
                                    <label for="">Nombres </label>
                                    <input class="form-control" id="nameUser" name="nameUser">
                                </div> -->
                                <div class="mb-1">
                                    <label><b>Configuración Básica</b></label>
                                </div>

                                <div class="mb-1">
                                    <label><b>Configuración Productos</b></label>
                                </div>

                                <div class="mb-1">
                                    <label><b>Configuración General</b></labellass=>
                                </div>

                                <div class="mb-1">
                                    <label><b>Creación Usuarios</b></labels=>
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-1" type="checkbox">
                                    <label for="checkbox-1">Crear Productos</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-5" type="checkbox">
                                    <label for="checkbox-5">Productos / Materias Primas</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-10" type="checkbox">
                                    <label for="checkbox-10">Cargar Nómina</label>
                                </div>


                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-13" type="checkbox">
                                    <label for="checkbox-13">Usuarios</label>
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-2" type="checkbox">
                                    <label for="checkbox-2">Crear Materiales</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-6" type="checkbox">
                                    <label for="checkbox-6">Productos / Procesos</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-11" type="checkbox">
                                    <label for="checkbox-11">Asignar Gastos</label>
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-3" type="checkbox">
                                    <label for="checkbox-3">Crear Máquinas</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-7" type="checkbox">
                                    <label for="checkbox-7">Carga Fabril</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-12" type="checkbox">
                                    <label for="checkbox-12">Distribuir Gastos</label>
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                </div>

                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-4" type="checkbox">
                                    <label for="checkbox-4">Crear Procesos</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-8" type="checkbox">
                                    <label for="checkbox-8">Servicios Externos</label>
                                </div>
                                <div class="checkbox checkbox-success checkbox-circle mb-1">
                                    <input id="checkbox-9" type="checkbox">
                                    <label for="checkbox-9">Lineas de Producto</label>
                                </div>


                                <div class="form-group floating-label enable-floating-label show-label btnCreateAccessUser">
                                    <button class="btn btn-success" id="btnCreateUserAndAccess">Crear Accesos Usuario</button>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tblUsers">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../../app/js/users/users.js"></script>
<script src="../../../app/js/users/tblUsers.js"></script>