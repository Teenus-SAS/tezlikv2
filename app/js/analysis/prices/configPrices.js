$(document).ready(function () {
  $(document).on('click', '.seeDetail', function (e) {
    let id_product = this.id;
    $.ajax({
      type: 'GET',
      url: `/api/dashboardPricesProducts/${id_product}`,
      success: function (r) {
        debugger;
        dataPrice = JSON.stringify(r);
        sessionStorage.setItem('dataPrice', dataPrice);

        $('#rawMaterial').val(dataPrice.cost_materials);
      },
    });
  });
});
