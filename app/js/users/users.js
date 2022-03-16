$(document).ready(function() {
    /* Ocultar panel Nuevo usuario */

    $('.cardCreateUsers').hide();
    $('.cardCreateAccessUser').hide();

    /* Abrir panel Nuevo usuario */

    $('#btnNewUser').click(function(e) {
        e.preventDefault();
        $('.cardCreateUsers').toggle(800);
        $('.cardCreateAccessUser').toggle(800);
        $('#btnCreateUserAndAccess').html('Crear Usuario y Accesos');

        sessionStorage.removeItem('id_user');

        $('#formCreateUser').trigger('reset');
    });

    /* Agregar nuevo usuario */

    $('#btnCreateUser').click(function(e) {
        e.preventDefault();
        let idUser = sessionStorage.getItem('id_user');

        if (idUser == '' || idUser == null) {}
    });
});