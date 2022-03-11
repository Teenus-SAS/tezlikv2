$(document).ready(function() {

    let idProduct
        /* Ocultar panel crear producto */

    $('.cardAddProcess').hide();

    /* Abrir panel crear producto */

    $('#btnCreateProcess').click(function(e) {
        e.preventDefault();

        //$('.cardCreateRawMaterials').hide();
        $('.cardAddProcess').toggle(800);
        $('#btnAddProcess').html('Asignar');

        $('#idProcess').val('');
        $('#idMachine').val('');
        $('#enlistmentTime').val('');
        $('#operationTime').val('');
        $('#totalTime').val('');
    });

    /* Seleccionar producto */

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        idProduct = $('#selectNameProduct').val();
    });

    /* calcular el tiempo total proceso */

    $(document).on('click keyup', '#enlistmentTime', function(e) {

        tOperation = $('#operationTime').val();

        tOperation == '' ? tOperation = 0 : tOperation
        this.value == '' ? this.value = 0 : this.value

        let val = parseFloat(this.value) + parseFloat(tOperation)
        $('#totalTime').val(val);
    });

    $(document).on('click keyup', '#operationTime', function(e) {

        tEnlistment = $('#enlistmentTime').val();

        tEnlistment == '' ? tEnlistment = 0 : tEnlistment
        this.value == '' ? this.value = 0 : this.value

        let val = parseFloat(this.value) + parseFloat(tEnlistment)
        $('#totalTime').val(val);
    });


    /* Adicionar nuevo proceso */

    $('#btnAddProcess').click(function(e) {

        e.preventDefault();
        let idProductProcess = sessionStorage.getItem('id_product_process')

        if (idProductProcess == '' || idProductProcess == null) {

            idProduct = parseInt($('#selectNameProduct').val());
            refP = parseInt($('#idProcess').val());
            refM = parseInt($('#idMachine').val());

            enlisT = parseInt($('#enlistmentTime').val());
            operT = parseInt($('#operationTime').val())
            totalTime = parseInt($('#totalTime').val())

            data = idProduct * refP * refM

            if (!data || totalTime == 0 || totalTime == '') {
                toastr.error('Ingrese todos los campos')
                return false
            }

            productProcess = $('#formAddProcess').serialize();

            productProcess = productProcess + '&idProduct=' + idProduct;

            $.post("../../api/addProductsProcess", productProcess,
                function(data, textStatus, jqXHR) {
                    message(data);
                },
            );
        } else {
            updateProcess();
        }
    });

    /* Actualizar productos Procesos */

    $(document).on('click', '.updateProcess', function(e) {
        
        $('.cardAddProcess').show(800);
        $('#btnAddProcess').html('Actualizar');

        let row = $(this).parent().parent()[0]
        let data = tblConfigProcess.fnGetData(row)

        sessionStorage.setItem('id_product_process', data.id_product_process)

        $('#idProcess').val(data.process);
        $('#idMachine').val(data.machine);
        $('#enlistmentTime').val(data.enlistment_time);
        $('#operationTime').val(data.operation_time);
        $('#totalTime').val(data.total_time);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });

    updateProcess = () => {
        let data = $('#formAddProcess').serialize();
        idProduct = $('#selectNameProduct').val();
        idProductProcess = sessionStorage.getItem('id_product_process')
        data = data + '&idProductProcess=' + idProductProcess + '&idProduct=' + idProduct

        $.post("../../api/updateProductsProcess", data,
            function(data, textStatus, jqXHR) {
                message(data)
            },
        );
    }

    /* Eliminar proceso */

    $(document).on('click', '.deleteProcess', function(e) {
        
        let id_product_process = this.id
        $.get(`../../api/deleteProductProcess/${id_product_process}`,
            function(data, textStatus, jqXHR) {
                message(data)
            }
        )
    });

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            // $('.cardCreateRawMaterials').toggle(800);
            $('.cardAddProcess').hide(800);
            $("#formAddProcess")[0].reset();
            updateTable()
            toastr.success(data.message)
                //return false
        } else if (data.error == true)
            toastr.error(data.message)
        else if (data.info == true)
            toastr.info(data.message)
    }

    /* Actualizar tabla */

    function updateTable() {
        $('#tblConfigProcess').DataTable().clear()
        $('#tblConfigProcess').DataTable().ajax.reload()
    }
});