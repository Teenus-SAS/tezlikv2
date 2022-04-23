$(document).ready(function () {
  loadTableRawMaterialsAnalisys = (idProduct) => {
    $('#unitsmanufacturated').val('');
    $('#unitsmanufacturated').click();

    $('.colMaterials').empty();
    $.ajax({
      type: 'GET',
      url: `/api/analysisRawMaterials/${idProduct}`,
      success: function (r) {
        if (r.length == 0) {
          $('.colMaterials').append(`
            <tr class="col">
                <th class="text-center" colspan="9">Ningún dato disponible en esta tabla =(</th>
            </tr>
                `);
          return false;
        } else {
          $('.empty').hide();
          $('.colMaterials').empty();
          for (i = 0; i < r.length; i++) {
            //<th id="reference-${i + 1}">${r[i].reference}</th>
            $('.colMaterials').append(
              `<tr class="col${i + 1} text-center" id="col${i + 1}">
                        <th scope="row">${i + 1}</th>
                        <th id="rawMaterial-${i + 1}">${r[i].material}</th>
                        <th id="quantity-${i + 1}">${r[i].quantity}</th>
                        <th id="actualPrice-${i + 1}">$ ${r[
                i
              ].cost.toLocaleString('es-ES')}</th>
                        <th><input class="form-control number negotiatePrice text-center" type="text" id="${
                          i + 1
                        }"></th>
                        <th id="percentage-${i + 1}"></th>
                        <th id="unityCost-${i + 1}">$ ${r[
                i
              ].unityCost.toLocaleString('es-ES')}</th>
                        <th id="totalCost-${i + 1}"></th>
                        <th id="projectedCost-${i + 1}"></th>
                    </tr>`
            );
          }
        }

        unitsmanufacturated(r.length);
      },
    });
  };

  // Calcular
  $(document).on('click keyup', '.negotiatePrice', function (e) {
    negotiatePrice = this.value;
    line = this.id;

    negotiatePrice = parseFloat(negotiatePrice);
    //negotiatePrice = negotiatePrice.replace('.', '');

    actualPrice = $(`#actualPrice-${line}`).html();
    actualPrice = actualPrice.replace('.', '').replace('$', '');
    actualPrice = parseFloat(actualPrice);

    // Calcular porcentaje
    percentage = 100 - (negotiatePrice / actualPrice) * 100;
    if (isNaN(negotiatePrice)) $(`#percentage-${line}`).html('');
    else $(`#percentage-${line}`).html(`${percentage.toFixed(2)} %`);

    unitsmanufacturated = $('#unitsmanufacturated').val();
    if (unitsmanufacturated == '') {
      $(`#projectedCost-${line}`).html('');
    } else {
      // Calcular costo proyectado
      quantity = $(`#quantity-${line}`).html();
      quantity = parseFloat(quantity);
      projectedCost = quantity * negotiatePrice * unitsmanufacturated;

      if (isNaN(projectedCost)) $(`#projectedCost-${line}`).html();
      else
        $(`#projectedCost-${line}`).html(
          `$ ${projectedCost.toLocaleString('es-ES')}`
        );
    }
  });

  unitsmanufacturated = (count) => {
    $(document).on('click keyup', '#unitsmanufacturated', function (e) {
      unitsmanufacturated = this.value;

      totalMonthlySavings = 0;
      for (i = 0; i < count; i++) {
        if (unitsmanufacturated == '') $(`#totalCost-${i + 1}`).html('');
        else {
          // Calcular Porcentaje y Calcular costo proyectado
          $('.negotiatePrice').click();

          // Calcular costo total
          unityCost = $(`#unityCost-${i + 1}`).html();
          unityCost = unityCost
            .replace('$', '')
            .replace('.', '')
            .replace('.', '');
          unityCost = parseFloat(unityCost);

          totalCost = unitsmanufacturated * unityCost;
          $(`#totalCost-${i + 1}`).html(
            `$ ${totalCost.toLocaleString('es-ES')}`
          );

          // Calcular ahorro mensual
          projectedCost = $(`#projectedCost-${i + 1}`).html();
          projectedCost = projectedCost
            .replace('$', '')
            .replace('.', '')
            .replace('.', '');
          projectedCost = parseFloat(projectedCost);

          monthlySavingsRow = totalCost - projectedCost;
          totalMonthlySavings = totalMonthlySavings + monthlySavingsRow;
        }
      }
      if (isNaN(totalMonthlySavings)) {
        $('#monthlySavings').val('');
        $('#annualSavings').val('');
      } else {
        $('#monthlySavings').val(
          `$ ${totalMonthlySavings.toLocaleString('es-ES')}`
        );
        // Calcular ahorro anual
        annualSavings = totalMonthlySavings * 12;
        $('#annualSavings').val(`$ ${annualSavings.toLocaleString('es-ES')}`);
      }
    });
  };
});
