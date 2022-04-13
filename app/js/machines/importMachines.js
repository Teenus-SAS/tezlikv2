$(document).ready(function () {
  let selectedFile;

  $('.cardImportMachines').hide();

  $('#btnNewImportMachines').click(function (e) {
    e.preventDefault();
    $('.cardCreateMachines').hide(800);
    $('.cardImportMachines').toggle(800);
  });

  $('#fileMachines').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportMachines').click(function (e) {
    e.preventDefault();

    file = $('#fileMachines').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        // console.log(data);
        checkMachine(data);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  /* Mensaje de advertencia */
  checkMachine = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/importMachines',
      data: { importMachines: data },
      success: function (r) {
        bootbox.confirm({
          title: '¿Desea continuar con la importación?',
          message: `Se han encontrado los siguientes registros:<br><br>Datos a insertar: ${r[0]} <br>Datos a actualizar: ${r[1]}`,
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
              saveMachineTable(data);
            }
          },
        });
      },
    });
  };

  saveMachineTable = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/addMachines',
      data: { importMachines: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportMachines').hide(800);
          $('#formImportMachines')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */
        function updateTable() {
          $('#tblMachines').DataTable().clear();
          $('#tblMachines').DataTable().ajax.reload();
        }
      },
    });
  };
});
