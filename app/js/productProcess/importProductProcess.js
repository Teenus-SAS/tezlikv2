$(document).ready(function () {
  let selectedFile;

  $('.cardImportProductsProcess').hide();

  $('#btnNewImportProductProcess').click(function (e) {
    debugger;
    e.preventDefault();
    $('.cardAddProcess').hide(800);
    $('.cardImportProductsProcess').toggle(800);
  });

  $('#fileProductsProcess').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportProductProcess').click(function (e) {
    e.preventDefault();

    file = $('#fileProductsProcess').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        // console.log(data);
        checkProductProcess(data);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  /* Mensaje de advertencia */
  checkProductProcess = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/importProductsProcess',
      data: { importProductsProcess: data },
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
              saveProductProcessTable(data);
            }
          },
        });
      },
    });
  };

  saveProductProcessTable = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/addProductsProcess',
      data: { importProductsProcess: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportProductsProcess').hide(800);
          $('#formImportProductProcess')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */
        function updateTable() {
          $('#tblConfigProcess').DataTable().clear();
          $('#tblConfigProcess').DataTable().ajax.reload();
        }
      },
    });
  };
});
