$(document).ready(function() {

    /* Ocultar panel crear procesos */

    $('.cardCreateProcess').hide();

    /* Abrir panel crear procesos */

    $('#btnNewProcess').click(function(e) {
        e.preventDefault();
        $('.cardCreateProcess').toggle(800);

        $('#btnCreateProcess').html('Crear Producto');
        $('#process').val('');
    });
});