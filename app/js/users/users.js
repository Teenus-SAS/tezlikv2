$(document).ready(function () {
  /* Ocultar panel Nuevo usuario */

  $('.cardCreateUsers').hide();

  /* Aprir panel Nuevo usuario */

  $('#btnNewUser').click(function (e) {
    e.preventDefault();
    $('.cardCreateUsers').toggle(800);
    $('#btnCreateUser').html('Crear Usuario');

    sessionStorage.removeItem('id_user');

    $('#formCreateUser').trigger('reset');
  });

  /* Agregar nuevo usuario */

  $('#btnCreateUser').click(function (e) {
    e.preventDefault();
    let idUser = sessionStorage.getItem('id_user');

    if (idUser == '' || idUser == null) {
    }
  });
});
