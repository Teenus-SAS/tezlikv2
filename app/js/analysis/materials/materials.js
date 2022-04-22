$(document).ready(function () {
  $('.cardTableRawMaterials').hide();
  $('.cardRawMaterialsAnalysis').hide();

  // Mostrar tabla composicion materias prima
  $('#btnComposition').click(function (e) {
    e.preventDefault();
    $('#btnComposition').prop('disabled', true);
    $('#btnRawMaterialsAnalysis').prop('disabled', false);

    $('.cardRawMaterialsAnalysis').hide();
    $('.cardTableRawMaterials').toggle(800);
  });

  // Mostrar tabla analisis de materia prima
  $('#btnRawMaterialsAnalysis').click(function (e) {
    e.preventDefault();
    $('#btnRawMaterialsAnalysis').prop('disabled', true);
    $('#btnComposition').prop('disabled', false);

    id = $('#selectNameProduct').val();

    if (id == null) {
      toastr.error('Seleccione un producto');
      return false;
    } else {
      $('.cardTableRawMaterials').hide();
      $('.cardRawMaterialsAnalysis').toggle(800);
      loadTableRawMaterialsAnalisys(id);
    }
  });

  const loadTableRawMaterialsAnalisys = (idProduct) => {
    $.ajax({
      type: 'GET',
      url: `/api/analysisRawMaterials/${idProduct}`,
      success: function (r) {
        if (r.length == 0) {
          $('.col1').hide();
          $('.col2').hide();
          $('.empty').toggle(800);
          return false;
        } else {
          $('.empty').hide();

          for (i = 0; i < r.length; i++) {
            $(`#reference${i + 1}`).html(r[i].reference);
            $(`#rawMaterial${i + 1}`).html(r[i].material);
            $(`#actualPrice${i + 1}`).html(r[i].cost);
            $(`#unityCost${i + 1}`).html(r[i].totalCost);
            // Calcular
          }
        }
      },
    });
  };
});
