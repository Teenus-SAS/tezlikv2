$(document).ready(function () {
  /* Ocultar panel Nueva carga fabril */

  $('.cardCreatePayroll').hide();
  $('#factor').prop('disabled', true);

  $('#btnCloseCardPayroll').click(function (e) {
    e.preventDefault();
    $('#createPayroll').modal('hide');
  });

  /* Abrir panel crear carga nomina */

  $('#btnNewPayroll').click(function (e) {
    e.preventDefault();
    $('#createPayroll').modal('show');
    $('#btnCreatePayroll').html('Crear');

    sessionStorage.removeItem('id_payroll');

    $('#formCreatePayroll').trigger('reset');

    // $('#employee').val('');
    // $('#idProcess option:contains(Seleccionar)').prop('selected', true);
    // $('#basicSalary').val('');
    // $('#transport').val('');
    // $('#endowment').val('');
    // $('#extraTime').val('');
    // $('#bonification').val('');
    // $('#workingHoursDay').val('');
    // $('#workingDaysMonth').val('');

    // $('#typeFactor option:contains(Seleccionar)').prop('selected', true);
    // $('#factor').val('');
  });

  /* Mostrar factor prestacional */

  $(document).on('click', '#typeFactor', function (e) {
    $('#factor').prop('disabled', true);

    if (this.value == 0) {
      ('Seleccione una opción');
    }
    if (this.value == 1) {
      value = 38;
    }
    if (this.value == 2) {
      value = 0;
    }
    if (this.value == 3) {
      $('#factor').prop('disabled', false);
      value = $('#factor').val();
    }

    $('#factor').val(value);
  });

  /* Agregar nueva carga nomina */

  $('#btnCreatePayroll').click(function (e) {
    e.preventDefault();
    let idPayroll = sessionStorage.getItem('id_payroll');

    if (idPayroll == '' || idPayroll == null) {
      employee = $('#employee').val();
      process = parseInt($('#idProcess').val());

      salary = $('#basicSalary').val();
      transport = $('#transport').val();
      endowment = $('#endowment').val();
      extraT = $('#extraTime').val();
      bonification = $('#bonification').val();

      workingHD = parseInt($('#workingHoursDay').val());
      workingDM = parseInt($('#workingDaysMonth').val());
      //factor = parseInt($('#typeFactor').val());

      data = process * workingDM * workingHD;
      income = salary * transport * endowment * extraT * bonification;

      if (!data || income == null || process == '' || process == 0) {
        toastr.error('Ingrese todos los campos');
        return false;
      }

      $('#factor').prop('disabled', false);

      payroll = $('#formCreatePayroll').serialize();

      $.post(
        '../../api/addPayroll',
        payroll,
        function (data, textStatus, jqXHR) {
          $('#factor').prop('disabled', true);
          $('#createPayroll').modal('hide');
          message(data);
        }
      );
    } else {
      updatePayroll();
    }
  });

  /* Actualizar nomina */

  $(document).on('click', '.updatePayroll', function (e) {
    $('#createPayroll').modal('show');
    $('#btnCreatePayroll').html('Actualizar');

    let row = $(this).parent().parent()[0];
    let data = tblPayroll.fnGetData(row);
    debugger;

    let idPayroll = sessionStorage.getItem('id_payroll');

    let payrollData = sessionStorage.getItem('payrollData');
    payrollData = JSON.parse(payrollData);

    for (let i = 0; i < payrollData.length; i++) {
      if (idPayroll == payrollData[i]['id_payroll']) {
        employee = payrollData[i]['employee'];
        idProcess = payrollData[i]['process'];
        basicSalary = payrollData[i]['salary'];
        transport = payrollData[i]['transport'];
        endowment = payrollData[i]['endowment'];
        extraTime = payrollData[i]['extra_time'];
        bonification = payrollData[i]['bonification'];
        workingHoursDay = payrollData[i]['hours_day'];
        workingDaysMonth = payrollData[i]['working_days_month'];
        typeFactor = payrollData[i]['type_contract'];
        factorBenefit = payrollData[i]['factor_benefit'];
        break;
      }
    }

    $('#employee').val(employee);

    //$('#idProcess option[value=' + idProcess + ']').attr('selected', true);

    $('#basicSalary').val(basicSalary);
    $('#transport').val(transport);
    $('#endowment').val(endowment);
    $('#extraTime').val(extraTime);
    $('#bonification').val(bonification);

    $('#workingHoursDay').val(workingHoursDay);
    $('#workingDaysMonth').val(workingDaysMonth);

    //$('#typeFactor option[value=' + typeFactor + ']').attr('selected', true);
    $('#factor').val(data.factorBenefit);

    $('html, body').animate(
      {
        scrollTop: 0,
      },
      1000
    );
  });

  updatePayroll = () => {
    debugger;
    $('#factor').prop('disabled', false);
    let data = $('#formCreatePayroll').serialize();
    idPayroll = sessionStorage.getItem('id_payroll');
    data = data + '&idPayroll=' + idPayroll;

    $.post('../../api/updatePayroll', data, function (data, textStatus, jqXHR) {
      $('#factor').prop('disabled', true);
      $('#createPayroll').modal('hide');
      message(data);
    });
  };

  /* Eliminar carga nomina */

  $(document).on('click', '.deletePayroll', function (e) {
    let id_payroll = this.id;

    $.get(
      `../../api/deletePayroll/${id_payroll}`,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  });

  /* Mensaje de exito */

  message = (data) => {
    if (data.success == true) {
      $('.cardCreatePayroll').hide(800);
      $('#formCreatePayroll')[0].reset();
      updateTable();
      toastr.success(data.message);
      return false;
    } else if (data.error == true) toastr.error(data.message);
    else if (data.info == true) toastr.info(data.message);
  };

  /* Actualizar tabla */

  function updateTable() {
    $('#tblPayroll').DataTable().clear();
    $('#tblPayroll').DataTable().ajax.reload();
  }
});
