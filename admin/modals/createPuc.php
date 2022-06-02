<div class="modal fade" id="createPUC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <div class="page-content-wrapper mt--45">
                    <div class="container-fluid">
                        <div class="vertical-app-tabs" id="rootwizard">
                            <div class="col-md-12 col-lg-12 InputGroup">
                                <form id="formCreatePuc">
                                    <div class="row mt-5">
                                        <div class="col-12 col-lg-12 titlePayroll pt-2">
                                            <label for=""><b>Cuenta</b></label>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group floating-label enable-floating-label show-label">
                                                <input id="accountNumber" name="accountNumber" type="text" class="form-control">
                                                <label for="accountNumber">Número de cuenta<span class="text-danger">*</span></label>
                                                <div class="validation-error d-none font-size-13">Requerido</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group floating-label enable-floating-label show-label">
                                                <input id="account" name="account" type="text" class="form-control">
                                                <label for="account">cuenta<span class="text-danger">*</span></label>
                                                <div class="validation-error d-none font-size-13">Requerido</div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row justify-content-md-end">
                                        <button type="button" class="col-6 col-sm-2 btn btn-secondary m-2" data-bs-dismiss="modal" id="btnClosePuc">Cerrar</button>
                                        <button type="submit" class="col-6 col-sm-2 btn btn-primary m-2" id="btnCreatePuc">Crear</button>
                                    </div>
                                    <!-- This button link with id-sw-default-step-1 if you change it change in serial number like below
                                    <div class="d-none">
                                        <button class="btn btn-primary" id="btn">submit</button>
                                    </div> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCloseCardPayroll">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnCreatePayroll">Crear</button>
            </div> -->
        </div>
    </div>
</div>