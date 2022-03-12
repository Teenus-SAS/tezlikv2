$(document).ready(function () {
  $('.cardCreateExpenses').hide();

  $('#btnNewExpense').click(function (e) {
    debugger;
    e.preventDefault();

    $('.cardCreateExpenses').toggle(800);
    $('#btnCreateExpenses').html('Crear');

    sessionStorage.removeItem('id_expense');

    $('#idPuc option:contains(Seleccionar)').prop('selected', true);
    $('#value').val('');
  });

  $('#btnCreateExpenses').click(function (e) {
    debugger;
    e.preventDefault();

    let idExpense = sessionStorage.getItem('id_expense');

    if (idExpense == '' || idExpense == null) {
      puc = parseInt($('#idPuc').val());
      value = parseInt($('#value').val());

      data = puc * value;

      if (!data) {
        toastr.error('Ingrese todos los campos');
        return false;
      }

      expenses = $('#formCreateExpenses').serialize();

      $.post(
        '../../api/addExpenses',
        expenses,
        function (data, textStatus, jqXHR) {
          message(data);
        }
      );
    } else {
      updateExpenses();
    }
  });

  $(document).on('click', '.updateExpenses', function (e) {
    $('.cardCreateExpenses').show(800);
    $('#btnCreateExpense').html('Actualizar');

    let row = $(this).parent().parent()[0];
    let data = tblExpenses.fnGetData(row);

    sessionStorage.setItem('id_expense', data.id_expense);

    $(`#idPuc option[value=${data.id_puc}]`).attr('selected', true);
    $('#value').val(data.count);

    $('html, body').animate(
      {
        scrollTop: 0,
      },
      1000
    );
  });

  updateExpenses = () => {
    let data = $('#formCreateExpenses').serialize();
    idExpense = sessionStorage.getItem('id_expense');
    data = data + '&idExpense=' + idExpense;

    $.post(
      '../../api/updateExpenses',
      data,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  };

  $(document).on('click', '.deleteExpenses', function (e) {
    let id_expense = this.id;
    $.get(
      `../../api/deleteExpenses/${id_expense}`,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  });

  /* Mensaje de exito */

  message = (data) => {
    if (data.success == true) {
      $('.cardCreateExpenses').hide(800);
      $('#formCreateExpenses')[0].reset();
      updateTable();
      toastr.success(data.message);
      //return false
    } else if (data.error == true) toastr.error(data.message);
    else if (data.info == true) toastr.info(data.message);
  };

  /* Actualizar tabla */

  function updateTable() {
    $('#tblExpenses').DataTable().clear();
    $('#tblExpenses').DataTable().ajax.reload();
  }
});
