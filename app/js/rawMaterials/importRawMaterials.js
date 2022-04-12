$(document).ready(function () {
  let selectedFile;

  $('.cardImportMaterials').hide();

  $('#btnNewImportMaterials').click(function (e) {
    e.preventDefault();
    $('.cardRawMaterials').hide(800);
    $('.cardImportMaterials').toggle(800);
  });

  $('#fileMaterials').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportMaterials').click(function (e) {
    e.preventDefault();

    file = $('#fileMaterials').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        // console.log(data);
        checkProduct(data);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  /* Mensaje de advertencia */
  checkProduct = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/importMaterial',
      //data: data,
      data: { importProducts: data },
      success: function (r) {
        bootbox.confirm({
          title: 'Desea continuar con la importación?',
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
            saveProductTable(data);
          },
        });
      },
    });
  };

  saveMaterialTable = (data) => {
    $.ajax({
      type: 'POST',
      url: '../api/addMaterials',
      data: { importMaterials: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportMaterials').hide(800);
          $('#formImportMaterials')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */
        function updateTable() {
          $('#tblRawMaterials').DataTable().clear();
          $('#tblRawMaterials').DataTable().ajax.reload();
        }
      },
    });
  };
});