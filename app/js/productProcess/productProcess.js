$(document).ready(function () {

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

    /* Adicionar nuevo proceso */

    $('#btnAddProcess').click(function(e){
        debugger
        e.preventDefault();
        let idProductProcess = sessionStorage.getItem('id_product_process')

        if(idProductProcess == '' || idProductProcess == null) {
            refP = $('#idProcess').val();
            refM = $('#idMachine').val();
            enlisT = $('#enlistmentTime').val();
            operT = $('#operationTime').val();
            idProduct = $('#selectNameProduct').val();

            if(
                refP == '' || refP == 0 || refM == '' || refM == 0 || enlisT == '' || enlisT == 0 ||
                operT == '' || operT == 0 ) {
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
        debugger
        $('.cardAddProcess').show(800);
        $('#btnAddProcess').html('Actualizar');

        let row = $(this).parent().parent()[0]
        let data = tblConfigProcess.fnGetData(row)

        sessionStorage.setItem('id_product_process', data.id_product_process)

        $('#idProcess').val(data.process);
        $('#idMachine').val(data.machine);
        $('#enlistmentTime').val(data.enlistment_time);
        $('#operationTime').val(data.operation_time);
        //$('#totalTime').val(data.total_time);

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
        debugger
        let id_product_process = this.id
        $.get(`../../api/deleteProductProcess/${id_product_process}`,
            function(data, textStatus, jqXHR){
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