$(document).ready(function() {

    /* Ocultar panel para crear Machinees */

    $('.cardCreateMachines').hide();

    /* Abrir panel para crear Machinees */

    $('#btnCreateMachine').click(function(e) {
        e.preventDefault();
        $('.cardCreateMachines').toggle(800);
        $('#idMachine').val('');
        $('#btnCreateMachine').html('Crear');

        $('#idMachine').val('');
        $('#machine').val('');
        $('#price').val('');
        $('#residualValue').val('');
        $('#depreciationYears').val('');
        $('#depreciationMinute').val('');
    });

    /* Calcular depreciación */

    $('#price, #residualValue, #depreciationYears').keyup(function(e) {

        let price = $('#price').val();
        let residualValue = $('#residualValue').val();
        let yearsDepreciation = $('#depreciationYears').val();

        price = price.replace('.', '')
        price = parseFloat(price)

        residualValue = residualValue.replace('.', '')
        residualValue = parseFloat(residualValue)

        yearsDepreciation = yearsDepreciation.replace('.', '')
        yearsDepreciation = parseFloat(yearsDepreciation)

        isNaN(price) ? price = 1 : price
        isNaN(residualValue) ? residualValue = 1 : residualValue
        isNaN(yearsDepreciation) ? yearsDepreciation = 1 : yearsDepreciation

        value = (price - residualValue) / 60 * yearsDepreciation / 60 / 60
        number = value.toLocaleString('en-US', { maximumFractionDigits: 2 })
        $('#depreciationMinute').val(number);

    });

    /* Crear producto */

    $('#btnCreateMachine').click(function(e) {
        e.preventDefault();
        let idMachine = $('#idMachine').val();

        if (idMachine == '') {
            ref = $('#refRawMachine').val();
            Machine = $('#nameRawMachine').val();
            unity = $('#unityRawMachine').val();
            cost = $('#costRawMachine').val();

            if (ref == '' || ref == 0 || Machine == '' || Machine == 0 || unity == '' || unity == 0 || cost == '' || cost == 0) {
                toastr.error('Ingrese todos los campos')
                return false
            }

            Machine = $('#formCreateMachine').serialize();

            $.post("../../../api/addMachines", Machine,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateMachine();
        }
    });

    /* Actualizar productos */

    $(document).on('click', '.updateRawMachines', function(e) {

        $('.cardRawMachines').show(800);

        $('#idMachine').val('');
        $('#btnCreateMachine').html('Actualizar');

        let row = $(this).parent().parent()[0]
        let data = tblRawMachines.fnGetData(row)

        $('#idMachine').val(data.id_Machine);
        $('#refRawMachine').val(data.reference);
        $('#nameRawMachine').val(data.Machine);
        $('#unityRawMachine').val(data.unit);
        $('#costRawMachine').val(data.cost);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    })

    updateMachine = () => {
        debugger
        let data = $('#formCreateMachine').serialize();
        $.post("../../../api/updateMachines", data,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    }

    /* Eliminar productos */

    $(document).on('click', '.deleteRawMachines', function(e) {
        debugger
        let id_Machine = this.id
        $.get(`../../../api/deleteMachine/${id_Machine}`,
            function(data, textStatus, jqXHR) {
                message(data)
            })
    })

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardRawMachines').hide(800);
            $("#formCreateMachine")[0].reset();
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
        $('#tblRawMachines').DataTable().clear()
        $('#tblRawMachines').DataTable().ajax.reload()
    }
});