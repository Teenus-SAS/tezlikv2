$(document).ready(function() {

    /* Ocultar panel para crear materiales */

    $('.cardRawMaterials').hide();

    /* Abriri panel para crear materiales */

    $('#btnNewMaterial').click(function(e) {
        e.preventDefault();
        $('#idMaterial').val('');
        $('.cardRawMaterials').toggle(800);
        $('#btnCreateMaterial').html('Crear');
    });

    /* Crear producto */

    $('#btnCreateMaterial').click(function(e) {
        e.preventDefault();
        let idMaterial = $('#idMaterial').val();

        if (idMaterial == '') {
            ref = $('#refRawMaterial').val();
            material = $('#nameRawMaterial').val();
            unity = $('#unityRawMaterial').val();
            cost = $('#costRawMaterial').val();

            if (ref == '' || ref == 0 || material == '' || material == 0 || unity == '' || unity == 0 || cost == '' || cost == 0) {
                toastr.error('Ingrese todos los campos')
                return false
            }

            Material = $('#formMaterial').serialize();

            $.post("../../../api/addMaterials", Material,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateMaterial();
        }
    });

    /* Actualizar productos */

    $(document).on('click', '.updateMaterials', function(e) {
        $('.cardMaterial').show(800);

        $('#idMaterial').val('');
        $('#btnCreateMaterial').html('Actualizar Materia Prima');

        let row = $(this).parent().parent()[0]
        let data = tblMaterials.fnGetData(row)

        $('#idMaterial').val(data.id_material);
        $('#referenceMaterial').val(data.reference);
        $('#material').val(data.product);
        $('#profitability').val(data.profitability);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    })

    updateMaterial = () => {
        let data = $('#formCreateMaterial').serialize();
        $.post("../../../api/updateMaterials", data,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    }

    /* Eliminar productos */

    $(document).on('click', '.deleteMaterials', function(e) {
        debugger
        let id_material = this.id
        $.get(`../../../api/deleteMaterial/${id_material}`,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    })

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardCreateProduct').hide();
            $("#formCreateProduct")[0].reset();
            updateTable()
            toastr.success(data.message)
        } else if (data.error == true)
            toastr.error(data.message)
        else if (data.info == true)
            toastr.info(data.message)
    }

});