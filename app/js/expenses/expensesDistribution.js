$(document).ready(function() {

    /* Carga gasto total */

    $.ajax({
        type: "POST",
        url: "../../../api/expenseTotal",
        success: function(r) {
            $('#expensesToDistribution').val(r.total_expense);
            //$('#expensesToDistribution').prop('disabled', true);
        }
    });


    /* Ocultar table de ingreso de datos volumen y unidades */

    $('.cardExpensesDistribution').hide();

    /* Abrir ventana para ingresar el volumen dy unidades de ventas para calcular gastos atribuibles al producto */

    $('#btnExpensesDistribution').click(function(e) {
        e.preventDefault();
        $('.cardExpensesDistribution').show(500);
        $('#btnAssignExpenses').html('Asignar');

        sessionStorage.removeItem('id_expenses_distribution');

        $('#refProduct option:contains(Seleccionar)').prop('selected', true);
        $('#selectNameProduct option:contains(Seleccionar)').prop('selected', true);
        $('#undVendidas').val('');
        $('#volVendidas').val('');

        $('#expensesToDistribution').val('');
    });

    $('#btnAssignExpenses').click(function(e) {
        e.preventDefault();
        let idExpensesDistribution = sessionStorage.getItem('id_expenses_distribution');

        if (idExpensesDistribution == '' || idExpensesDistribution == null) {
            refProduct = parseInt($('#refProduct').val());
            nameProduct = parseInt($('#selectNameProduct').val());
            unitExp = parseInt($('#undVendidas').val());
            volExp = parseInt($('#volVendidas').val());

            data = refProduct * nameProduct;
            exp = unitExp * volExp;

            if (!data || exp == null) {
                toastr.error('Ingrese todos los campos');
                return false;
            }

            expensesDistribution = $('#formExpensesDistribution').serialize();

            $.post(
                '../../api/addExpensesDistribution',
                expensesDistribution,
                function(data, textStatus, jqXHR) {
                    message(data);
                }
            );
        } else {
            updateExpensesDistribution();
        }
    });

    /* Actualizar gasto */

    $(document).on('click', '.updateExpenseDistribution', function(e) {
        $('.cardExpensesDistribution').show(500);
        $('#btnAssignExpenses').html('Actualizar');

        let row = $(this).parent().parent()[0];
        let data = tblExpensesDistribution.fnGetData(row);

        sessionStorage.setItem(
            'id_expenses_distribution',
            data.id_expenses_distribution
        );

        $(`#selectNameProduct option:contains(${data.product})`).prop(
            'selected',
            true
        );
        $(`#refProduct option:contains(${data.reference})`).prop('selected', true);
        $('#undVendidas').val(data.units_sold);
        $('#volVendidas').val(data.turnover);
        $('#expensesToDistribution').val(data.assignable_expense);

        $('html, body').animate({
                scrollTop: 0,
            },
            1000
        );
    });

    updateExpensesDistribution = () => {
        let data = $('#formExpensesDistribution').serialize();
        assignableExpense = $('#assignableExpense').val();
        idExpensesDistribution = sessionStorage.getItem('id_expenses_distribution');
        data =
            data +
            '&assignableExpense=' +
            assignableExpense +
            '&idExpensesDistribution=' +
            idExpensesDistribution;

        $.post(
            '../../api/updateExpensesDistribution',
            data,
            function(data, textStatus, jqXHR) {
                message(data);
            }
        );
    };

    /* Eliminar gasto */

    $(document).on('click', '.deleteExpenseDistribution', function(e) {
        debugger;
        let id_expenses_distribution = this.id;

        $.get(
            `../../api/deleteExpensesDistribution/${id_expenses_distribution}`,

            function(data, textStatus, jqXHR) {
                message(data);
            }
        );
    });

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardExpensesDistribution').hide(800);
            $('#formExpensesDistribution')[0].reset();
            updateTable();
            toastr.success(data.message);
            //return false;
        } else if (data.error == true) toastr.error(data.message);
        else if (data.info == true) toastr.info(data.message);
    };

    /* Actualizar tabla */

    function updateTable() {
        $('#tblExpensesDistribution').DataTable().clear();
        $('#tblExpensesDistribution').DataTable().ajax.reload();
    }
});