$(document).ready(function() {

    /* Ocultar panel para crear materiales */

    $('.cardCreateMachines').hide();

    /* Abrir panel para crear materiales */

    $('#btnCreateMachine').click(function(e) {
        e.preventDefault();
        $('.cardCreateMachines').toggle(800);
        $('#idMaterial').val('');
        $('#btnCreateMaterial').html('Crear');

        $('#idMaterial').val('');
        $('#refRawMaterial').val('');
        $('#nameRawMaterial').val('');
        $('#unityRawMaterial').val('');
        $('#costRawMaterial').val('');
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

            material = $('#formCreateMaterial').serialize();

            $.post("../../../api/addMaterials", material,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateMaterial();
        }
    });

    /* Actualizar productos */

    $(document).on('click', '.updateRawMaterials', function(e) {

        $('.cardRawMaterials').show(800);

        $('#idMaterial').val('');
        $('#btnCreateMaterial').html('Actualizar');

        let row = $(this).parent().parent()[0]
        let data = tblRawMaterials.fnGetData(row)

        $('#idMaterial').val(data.id_material);
        $('#refRawMaterial').val(data.reference);
        $('#nameRawMaterial').val(data.material);
        $('#unityRawMaterial').val(data.unit);
        $('#costRawMaterial').val(data.cost);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    })

    updateMaterial = () => {
        debugger
        let data = $('#formCreateMaterial').serialize();
        $.post("../../../api/updateMaterials", data,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    }

    /* Eliminar productos */

    $(document).on('click', '.deleteRawMaterials', function(e) {
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
            $('.cardRawMaterials').hide(800);
            $("#formCreateMaterial")[0].reset();
            toastr.success(data.message)
            updateTable()
            return false
        } else if (data.error == true)
            toastr.error(data.message)
        else if (data.info == true)
            toastr.info(data.message)
    }

    /* Actualizar tabla */

    function updateTable() {
        $('#tblRawMaterials').DataTable().clear()
        $('#tblRawMaterials').DataTable().ajax.reload()
    }
});