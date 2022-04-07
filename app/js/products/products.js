$(document).ready(function () {
  /* Ocultar panel crear producto */

  $('.cardCreateProduct').hide();

  /* Abrir panel crear producto */

  $('#btnNewProduct').click(function (e) {
    e.preventDefault();

    $('.cardCreateProduct').toggle(800);
    $('#btnCreateProduct').html('Crear Producto');

    sessionStorage.removeItem('id_product');

    $('#referenceProduct').val('');
    $('#product').val('');
    $('#profitability').val('');
    $('#commisionSale').val('');
  });

  /* Crear producto */

  $('#btnCreateProduct').click(function (e) {
    e.preventDefault();
    let idProduct = sessionStorage.getItem('id_product');

    if (idProduct == '' || idProduct == null) {
      ref = $('#referenceProduct').val();
      prod = $('#product').val();
      prof = $('#profitability').val();
      comission = $('#commisionSale').val();

      if (
        ref == '' ||
        ref == 0 ||
        prod == '' ||
        prod == 0 ||
        prof == '' ||
        prof == 0 ||
        comission == '' ||
        comission == 0
      ) {
        toastr.error('Ingrese todos los campos');
        return false;
      }
      debugger;
      product = $('#formCreateProduct').serialize();

      $.post(
        '../../api/addProducts',
        product,
        function (data, textStatus, jqXHR) {
          message(data);
        }
      );
    } else {
      updateProduct();
    }
  });

  /* Actualizar productos */

  $(document).on('click', '.updateProducts', function (e) {
    $('.cardCreateProduct').show(800);
    $('#btnCreateProduct').html('Actualizar Producto');

    idProduct = this.id;
    idProduct = sessionStorage.setItem('id_product', idProduct);

    let row = $(this).parent().parent()[0];
    let data = tblProducts.fnGetData(row);

    $('#referenceProduct').val(data.reference);
    $('#product').val(data.product);
    $('#profitability').val(data.profitability);
    $('#commisionSale').val(data.commision_sale);
    //$('#formFile').val(data.img);

    $('html, body').animate(
      {
        scrollTop: 0,
      },
      1000
    );
  });

  updateProduct = () => {
    let data = $('#formCreateProduct').serialize();
    idProduct = sessionStorage.getItem('id_product');
    data = data + '&idProduct=' + idProduct;

    $.post(
      '../../api/updateProducts',
      data,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  };

  /* Eliminar productos */

  $(document).on('click', '.deleteProducts', function (e) {
    let idProduct = this.id;
    dataProduct = {};
    dataProduct['idProduct'] = idProduct;

    bootbox.confirm({
      title: 'Eliminar',
      message:
        'Está seguro de eliminar este producto? Esta acción no se puede reversar.',
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
          $.post(
            '../../api/deleteProduct',
            dataProduct,
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
      $('.cardCreateProduct').hide(800);
      $('#formCreateProduct')[0].reset();
      updateTable();
      toastr.success(data.message);
      return false;
    } else if (data.error == true) toastr.error(data.message);
    else if (data.info == true) toastr.info(data.message);
  };

  /* Actualizar tabla */

  function updateTable() {
    $('#tblProducts').DataTable().clear();
    $('#tblProducts').DataTable().ajax.reload();
  }
});
