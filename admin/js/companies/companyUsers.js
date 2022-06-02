$(document).ready(function () {

    /* Mostrar todos los usuraios activos por empresa */
  
    $(document).on('click', '.updCompany', function (e) {
      e.preventDefault();
  
      company = $('#company').val();
      companyNIT = $('#companyNIT').val();
      companyCreator = $('#companyCreator').val();
      companyCreatedAt = $('#companyCreated_at').val();
      companyLogo = $('#companyLogo').val();
      companyCity = $('#companyCity').val();
      companyState = $('#companyState').val();
      companyCountry = $('#companyCountry').val();
      companyAddress = $('#companyAddress').val();
      companyTel = $('#companyTel').val();
  
      dataProduct = new FormData(document.getElementById('formCreateCompany'));
      dataProduct.append('id_company', id);
  
      $.ajax({
        type: 'POST',
        url: '/api/updateDataCompany',
        data: dataProduct,
        contentType: false,
        cache: false,
        processData: false,
  
        success: function (resp) {
          $('#createCompany').modal('hide');
          $('#formCreateCompany').val('');
          message(resp);
          updateTable();
        },
      });
    });
  
    /* Mensaje de exito */
  
    const message = (data) => {
      if (data.success == true) {
        $('#createCompany').hide(800);
        $('#formCreateCompany')[0].reset();
        updateTable();
        toastr.success(data.message);
        return false;
      } else if (data.error == true) toastr.error(data.message);
      else if (data.info == true) toastr.info(data.message);
    };
  
    /* Actualizar tabla */
  
    function updateTable() {
      $('#tblCompanies').DataTable().clear();
      $('#tblCompanies').DataTable().ajax.reload();
    }
  });
  