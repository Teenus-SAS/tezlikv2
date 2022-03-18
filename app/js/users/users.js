$(document).ready(function () {
  /* Ocultar panel Nuevo usuario */

  $('.cardCreateUsers').hide();
  $('.cardCreateAccessUser').hide();

  /* Abrir panel Nuevo usuario */

  $('#btnNewUser').click(function (e) {
    e.preventDefault();
    $('.cardCreateUsers').toggle(800);
    $('.cardCreateAccessUser').toggle(800);
    $('#btnCreateUserAndAccess').html('Crear Usuario y Accesos');

    sessionStorage.removeItem('id_user_access');

    $('#formCreateUser').trigger('reset');
    $('#formCreateAccessUser').trigger('reset');
  });

  /* Agregar nuevo usuario */

  $('#btnCreateUserAndAccess').click(function (e) {
    e.preventDefault();
    let idUserAccess = sessionStorage.getItem('id_user_access');

    if (idUserAccess == '' || idUserAccess == null) {
      debugger;
      nameU = $('#nameUser').val();
      lname = $('#lastnameUser').val();
      email = $('#emailUser').val();

      if (
        nameU == '' ||
        nameU == null ||
        lname == '' ||
        lname == null ||
        email == '' ||
        email == null
      ) {
        toastr.error('Ingrese nombre, apellido y email');
      }

      selected = new Array();
      $('#formCreateAccessUser input[type=checkbox]').each(function () {
        if (this.checked) {
          selected.push($(this).val());
        }
      });
      if (!selected != '') {
        //alert('Has seleccionado: ' + selected);
        toastr.error('Seleccione mas de un campo');
        return false;
      }

      user = $('#formCreateUser').serialize();

      $.post('../../api/addUser', user, function (data, textStatus, jqXHR) {
        message(data);
      });

      userAccess = $('#formCreateAccessUser').serialize();

      $.post(
        '../../api/addUserAccess',
        userAccess,
        function (data, textStatus, jqXHR) {
          message(data);
        }
      );

      //   chbx1 = $('#checkbox-1').is(':checked');
      //   chbx2 = $('#checkbox-2').is(':checked');
      //   chbx3 = $('#checkbox-3').is(':checked');
      //   chbx4 = $('#checkbox-4').is(':checked');
      //   chbx5 = $('#checkbox-5').is(':checked');
      //   chbx6 = $('#checkbox-6').is(':checked');
      //   chbx7 = $('#checkbox-7').is(':checked');
      //   chbx8 = $('#checkbox-8').is(':checked');
      //   chbx9 = $('#checkbox-9').is(':checked');
      //   chbx10 = $('#checkbox-10').is(':checked');
      //   chbx11 = $('#checkbox-11').is(':checked');
      //   chbx12 = $('#checkbox-12').is(':checked');
      //   chbx13 = $('#checkbox-13').is(':checked');
    } else {
      updateUserAccess();
    }
  });

  /* Actualizar User */

  $(document).on('click', '.updateUser', function (e) {
    debugger;
    $('.cardCreateUsers').show(800);
    $('.cardCreateAccessUser').show(800);
    $('#btnCreateUserAndAccess').html('Actualizar');

    let row = $(this).parent().parent()[0];
    let data = tblUsers.fnGetData(row);

    let idUserAccess = this.id;
    idUserAccess = sessionStorage.setItem('id_user_access', idUserAccess);

    $('#nameUser').val(data.firstname);
    $('#lastnameUser').val(data.lastname);
    $('#emailUser').val(data.email);

    if (data.create_product == 1) {
      $('#checkbox-1').prop('checked', true);
    }
    if (data.create_materials == 1) {
      $('#checkbox-2').prop('checked', true);
    }
    if (data.create_machines == 1) {
      $('#checkbox-3').prop('checked', true);
    }
    if (data.create_process == 1) {
      $('#checkbox-4').prop('checked', true);
    }
    if (data.product_materials == 1) {
      $('#checkbox-5').prop('checked', true);
    }
    if (data.product_process == 1) {
      $('#checkbox-6').prop('checked', true);
    }
    if (data.factory_load == 1) {
      $('#checkbox-7').prop('checked', true);
    }
    if (data.external_service == 1) {
      $('#checkbox-8').prop('checked', true);
    }
    if (data.product_line == 1) {
      $('#checkbox-9').prop('checked', true);
    }
    if (data.payroll_load == 1) {
      $('#checkbox-10').prop('checked', true);
    }
    if (data.expense == 1) {
      $('#checkbox-11').prop('checked', true);
    }
    if (data.expense_distribution == 1) {
      $('#checkbox-12').prop('checked', true);
    }
    if (data.user == 1) {
      $('#checkbox-13').prop('checked', true);
    }
  });

  updateUserAccess = () => {
    let dataUser = $('#formCreateUser').serialize();
    idUser = sessionStorage.getItem('id_user');
    dataUser = dataUser + '&idUser=' + idUser;

    $.post(
      '../../api/updateUser',
      dataUser,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );

    let dataUserAccess = $('#formCreateAccessUser').serialize();
    idUserAccess = sessionStorage.getItem('id_user_access');
    dataUserAccess = dataUserAccess + '&idUserAccess=' + idUserAccess;

    $.post(
      '../../api/updateUserAccess',
      dataUserAccess,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  };

  /* Eliminar usuario */

  $(document).on('click', '.deleteUser', function (e) {
    let idUser = this.id;

    bootbox.confirm({
      title: 'Eliminar',
      message:
        'Está seguro de eliminar este Usuario? Esta acción no se puede reversar.',
      buttons: {
        confirm: {
          label: 'Si',
          className: 'btn-success',
        },
        cancel: {
          label: 'No',
          className: 'btn-danger',
        },
      },
      callback: function (result) {
        if (result == true) {
          $.get(
            `../../api/deleteUser/${idUser}`,
            function (data, textStatus, jqXHR) {
              message(data);
            }
          );
        }
      },
    });
  });

  /* Mensaje de exito */

  message = (data) => {
    if (data.success == true) {
      $('.cardCreateUsers').hide(800);
      $('.cardCreateAccessUser').hide(800);
      $('#formCreateUser')[0].reset();
      $('#formCreateAccessUser')[0].reset();
      updateTable();
      toastr.success(data.message);
      return false;
    } else if (data.error == true) toastr.error(data.message);
    else if (data.info == true) toastr.info(data.message);
  };

  /* Actualizar tabla */

  function updateTable() {
    $('#tblUsers').DataTable().clear();
    $('#tblUsers').DataTable().ajax.reload();
  }
});
