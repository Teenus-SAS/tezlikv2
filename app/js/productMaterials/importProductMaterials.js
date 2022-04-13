$(document).ready(function () {
  let selectedFile;

  $('.cardImportProductsMaterials').hide();

  $('#btnNewImportProductsMaterials').click(function (e) {
    e.preventDefault();
    $('.cardAddMaterials').hide(800);
    $('.cardImportProductsMaterials').toggle(800);
  });

  $('#fileProductsMaterials').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportProductsMaterials').click(function (e) {
    e.preventDefault();

    file = $('#fileProductsMaterials').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        // console.log(data);
        checkProductMaterial(data);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  /* Mensaje de advertencia */
  checkProductMaterial = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/importProductsMaterials',
      data: { importProductsMaterials: data },
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
              saveProductMaterialTable(data);
            }
          },
        });
      },
    });
  };

  saveProductMaterialTable = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/addProductsMaterials',
      data: { importProductsMaterials: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportProductsMaterials').hide(800);
          $('#formImportProductMaterial')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */
        function updateTable() {
          $('#tblConfigMaterials').DataTable().clear();
          $('#tblConfigMaterials').DataTable().ajax.reload();
        }
      },
    });
  };
});