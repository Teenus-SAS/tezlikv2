$(document).ready(function () {
  let selectedFile;

  $('.cardImportProducts').hide();

  $('#btnNewImportProducts').click(function (e) {
    e.preventDefault();
    $('.cardCreateProduct').hide(800);
    $('.cardImportProducts').toggle(800);
  });

  $('#fileProducts').change(function (e) {
    e.preventDefault();
    selectedFile = e.target.files[0];
  });

  $('#btnImportProducts').click(function (e) {
    e.preventDefault();

    file = $('#fileProducts').val();

    if (!file) {
      toastr.error('Seleccione un archivo');
      return false;
    }

    importFile(selectedFile)
      .then((data) => {
        //console.log(data);
        saveProductTable(data);
      })
      .catch(() => {
        console.log('Ocurrio un error. Intente Nuevamente');
      });
  });

  saveProductTable = (data) => {
    $.ajax({
      type: 'POST',
      url: '../../api/addProducts',
      //data: data,
      data: { importProducts: data },
      success: function (r) {
        /* Mensaje de exito */
        if (r.success == true) {
          $('.cardImportProducts').hide(800);
          $('#formImportProduct')[0].reset();
          updateTable();
          toastr.success(r.message);
          return false;
        } else if (r.error == true) toastr.error(r.message);
        else if (r.info == true) toastr.info(r.message);

        /* Actualizar tabla */

        function updateTable() {
          $('#tblProducts').DataTable().clear();
          $('#tblProducts').DataTable().ajax.reload();
        }
      },
    });
  };
});
