$(document).ready(function () {
  let selectedFile;

  $('.cardImportExpensesAssignation').hide();

  $('#btnImportNewExpenses').click(function (e) {
    e.preventDefault();
    $('.cardCreateExpenses').hide();
    $('.cardImportExpensesAssignation').toggle(800);
  });

  $('#fileExpensesAssignation').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportExpensesAssignation').click(function (e) {
    e.preventDefault();

    file = $('#fileExpensesAssignation').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        let payrollToImport = data.map((item) => {
          return {
            employee: item.nombres_y_apellidos,
            process: item.proceso,
            basicSalary: item.salario_basico,
            transport: item.transporte,
            endowment: item.dotaciones,
            extraTime: item.horas_extras,
            bonification: item.otros_ingresos,
            workingHoursDay: item.horas_trabajo_x_dia,
            workingDaysMonth: item.dias_trabajo_x_mes,
            typeFactor: item.tipo_nomina,
            factor: item.factor,
          };
        });
        checkPayroll(payrollToImport);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  /* Mensaje de advertencia */
  checkPayroll = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/payrollDataValidation',
      data: { importPayroll: data },
      success: function (resp) {
        if (resp.error == true) {
          toastr.error(resp.message);
          $('#fileExpensesAssignation').val('');
          return false;
        }

        bootbox.confirm({
          title: '¿Desea continuar con la importación?',
          message: `Se han encontrado los siguientes registros:<br><br>Datos a insertar: ${resp.insert} <br>Datos a actualizar: ${resp.update}`,
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
              savePayroll(data);
            } else $('#fileExpensesAssignation').val('');
          },
        });
      },
    });
  };

  savePayroll = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/addPayroll',
      data: { importPayroll: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportExpensesAssignation').hide(800);
          $('#formImportExpesesAssignation')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */
        function updateTable() {
          $('#tblExpenses').DataTable().clear();
          $('#tblExpenses').DataTable().ajax.reload();
        }
      },
    });
  };
});
