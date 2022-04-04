$(document).ready(function () {
  /* Cargue Analisis de productos*/
  $.ajax({
    type: 'GET',
    url: `/api/dashboardPricesProducts`,
    success: function (r) {
      $('#rawMaterial').val(r.cost_materials);
    },
  });
});
