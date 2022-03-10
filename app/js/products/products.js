$(document).ready(function() {

    /* Ocultar panel crear producto */

    $('.cardCreateProduct').hide();

    /* Abrir panel crear producto */

    $('#btnNewProduct').click(function(e) {
        e.preventDefault();

        $('.cardCreateProduct').toggle(800);
        $('#btnCreateProduct').html('Crear Producto');

        $('#referenceProduct').val('');
        $('#product').val('');
        $('#profitability').val('');
    });


    /* Crear producto */

    $('#btnCreateProduct').click(function(e) {
        e.preventDefault();
        let idProduct = sessionStorage.getItem('id_product')

        if (idProduct == '') {
            ref = $('#referenceProduct').val();
            prod = $('#product').val();
            prof = $('#profitability').val();

            if (ref == '' || ref == 0 || prod == '' || prod == 0 || prof == '' || prof == 0) {
                toastr.error('Ingrese todos los campos')
                return false
            }

            product = $('#formCreateProduct').serialize();

            $.post("../../../api/addProducts", product,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateProduct();
        }
    });

    /* Actualizar productos */

    $(document).on('click', '.updateProducts', function(e) {

        $('.cardCreateProduct').show(800);
        $('#btnCreateProduct').html('Actualizar Producto');

        let row = $(this).parent().parent()[0]
        let data = tblProducts.fnGetData(row)

        sessionStorage.setItem('id_product', data.id_product)

        $('#referenceProduct').val(data.reference);
        $('#product').val(data.product);
        $('#profitability').val(data.profitability);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });


    updateProduct = () => {
        let data = $('#formCreateProduct').serialize();
        $.post("../../../api/updateProducts", data,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    }

    /* Eliminar productos */

    $(document).on('click', '.deleteProducts', function(e) {
        let id_product = this.id
        $.get(`../../../api/deleteProduct/${id_product}`,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    })


    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardCreateProduct').hide(800);
            $("#formCreateProduct")[0].reset();
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
        $('#tblProducts').DataTable().clear()
        $('#tblProducts').DataTable().ajax.reload()
    }


});