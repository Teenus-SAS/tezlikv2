$(document).ready(function() {

    /* Ocultar panel crear producto */

    $('.cardCreateProduct').hide();

    /* Abrir panel crear producto */

    $('#btnNewProduct').click(function(e) {
        e.preventDefault();
        $('.cardCreateProduct').toggle(800);
    });


    /* Crear producto */

    $('#btnCreateProduct').click(function(e) {
        e.preventDefault();
        debugger
        ref = $('referenceProduct').val();
        prod = $('product').val();
        prof = $('profitability').val();

        if (ref == '' || ref == 0 || prod == '' || prod == 0 || prof == '' || prof == 0) {
            toastr.error('Ingrese todos los campos')
            return false
        }

        product = $('#formCreateProduct').serialize();

        $.post("../../../api/addProducts", product,
            function(data, textStatus, jqXHR) {

                if (data.success == true) {
                    $('.cardCreateProduct').hide();
                    $("#formCreateProduct")[0].reset();
                    updateTable()
                    toastr.success(data.message)
                } else if (data.error == true)
                    toastr.error(data.message)
                else if (data.info == true)
                    toastr.info(data.message)

            },
        );
    });


    /* Actualizar tabla */

    function updateTable() {
        $('#tblProducts').DataTable().clear()
        $('#tblProducts').DataTable().ajax.reload()
    }


});