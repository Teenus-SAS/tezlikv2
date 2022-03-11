$(document).ready(function() {

    let idProduct

    /* Ocultar panel crear producto */

    $('.cardAddMaterials').hide();

    /* Abrir panel crear producto */

    $('#btnCreateProduct').click(function(e) {
        e.preventDefault();

        $('.cardAddMaterials').toggle(800);
        $('#btnAddMaterials').html('Asignar');

        $('#material').val('');
        $('#quantity').val('');
        $('#unity').val('');

    });

    /* Seleccionar producto */

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        idProduct = $('#selectNameProduct').val();
    });


    /* Adicionar nueva materia prima */

    $('#btnAddMaterials').click(function(e) {
        e.preventDefault();
        let idProductMaterial = sessionStorage.getItem('id_product_material')

        if (idProductMaterial == '' || idProductMaterial == null) {
            debugger
            ref = $('#material').val();
            quan = $('#quantity').val();
            idProduct = $('#selectNameProduct').val();

            if (ref == '' || ref == 0 || quan == '' || quan == 0) {
                toastr.error('Ingrese todos los campos')
                return false
            }

            productMaterial = $('#formAddMaterials').serialize();

            productMaterial = productMaterial + '&idProduct=' + idProduct;

            $.post("../../api/addProductsMaterials", productMaterial,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateMaterial();
        }
    });

    /* Actualizar productos materials */

    $(document).on('click', '.updateMaterials', function(e) {
        $('.cardAddMaterials').show(800);
        $('#btnAddMaterials').html('Actualizar');

        let row = $(this).parent().parent()[0]
        let data = tblConfigMaterials.fnGetData(row)

        sessionStorage.setItem('id_product_material', data.id_product_material)

        $('#material').val(data.materia);
        $('#quantity').val(data.cantidad);
        //$('#unity').val(data.unidad);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });

    updateMaterial = () => {
        let data = $('#formAddMaterials').serialize();
        idProduct = $('#selectNameProduct').val();
        idProductMaterial = sessionStorage.getItem('id_product_material');
        data = data + '&idProductMaterial=' + idProductMaterial + '&idProduct=' + idProduct

        $.post("../../api/updateProductsMaterials", data,
            function(data, textStatus, jqXHR) {
                message(data)
            },
        );
    }

    /* Eliminar materia prima */

    $(document).on('click', '.deleteMaterials', function(e) {
        let id_product_material = this.id
        $.get(`../../api/deleteProductMaterial/${id_product_material}`,
            function(data, textStatus, jqXHR) {
                message(data)
            }
        )
    });

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardAddMaterials').hide(800);
            $("#formAddMaterials")[0].reset();
            updateTable();
            toastr.success(data.message)
                //return false
        } else if (data.error == true)
            toastr.error(data.message)
        else if (data.info == true)
            toastr.info(data.message)
    }

    /* Actualizar tabla */

    function updateTable() {
        $('#tblConfigMaterials').DataTable().clear();
        $('#tblConfigMaterials').DataTable().ajax.reload();
    }
});