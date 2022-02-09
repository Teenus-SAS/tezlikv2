$(document).ready(function() {

    /* Ocultar table de ingreso de datos volumen y unidades */

    $('.cardExpensesDistribution').hide();

    /* Abrir ventana para ingresar el volumen dy unidades de ventas para calcular gastos atribuibles al producto */

    $('#btnExpensesDistribution').click(function(e) {
        e.preventDefault();
        $('.cardExpensesDistribution').show(500);
    });

    /* Sincronizar selects referencia y nombre producto */

    $('#refProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#selectNameProduct option[value=${id}]`).attr("selected", true);
    });

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#refProduct option[value=${id}]`).attr("selected", true);
    });


});