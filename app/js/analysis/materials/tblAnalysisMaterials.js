$(document).ready(function() {
    loadTableRawMaterialsAnalisys = (idProduct) => {
        $.ajax({
            type: 'GET',
            url: `/api/analysisRawMaterials/${idProduct}`,
            success: function(r) {
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
                        $('.colMaterials').append(
                            `<tr class="col${i + 1}" id="col${i + 1}">
                        <th scope="row">1</th>
                        <th id="reference-${i + 1}">${r[i].reference}</th>
                        <th id="rawMaterial-${i + 1}">${r[i].material}</th>
                        <th id="actualPrice-${i + 1}">$ ${r[i].cost.toLocaleString('es-ES')}</th>
                        <th><input class="form-control number negotiatePrice text-center" type="text" id="${i + 1}"></th>
                        <th id="percentage-${i + 1}"></th>
                        <th id="unityCost-${i + 1}">$ ${r[i].unityCost.toLocaleString('es-ES')}</th>
                        <th id="monthCost-${i + 1}"></th>
                        <th id="projectedCost-${i + 1}"></th>
                    </tr>`
                        );
                    }
                }
            },
        });

        /* fetch(`/api/analysisRawMaterials/${idProduct}`)
          .then((response) => response.text())
          .then((data) => {
            data = JSON.parse(data);
            tblAnalysisRawMaterials(data);
            calculate(data);
          }); */
    };

    /* tblAnalysisRawMaterials = (data) => {
        debugger;
        if (data.length == 0) {
            $('.colMaterials').append(`
            <tr class="col">
                <th class="text-center" colspan="9">Ningún dato disponible en esta tabla =(</th>
            </tr>
                `);
            return false;
        } else {
            $('.colMaterials').empty();
            for (i = 0; i < data.length; i++) {
                $('.colMaterials').append(
                    `<tr class="col${i + 1}" id="col${i + 1}">
            <th scope="row">1</th>
            <th id="reference${i + 1}">${data[i].reference}</th>
            <th id="rawMaterial${i + 1}">${data[i].material}</th>
            <th id="actualPrice${i + 1}">$ ${data[i].cost.toLocaleString(
            'es-ES'
          )}</th>
            <th><input class="form-control number" type="text" id="negotiatePrice"></th>
            <th id="percentage${i + 1}"></th>
            <th id="unityCost${i + 1}">$ ${data[i].unityCost.toLocaleString(
            'es-ES'
          )}</th>
            <th id="monthCost${i + 1}"></th>
            <th id="projectedCost${i + 1}"></th>
            </tr>`
                );
            }
        }
    }; */

    /* calculate = (data) => { */
    // Calcular
    $(document).on('click keyup', '.negotiatePrice', function(e) {

        negotiatePrice = this.value;
        line = this.id

        negotiatePrice = parseFloat(negotiatePrice);
        //negotiatePrice = negotiatePrice.replace('.', '');

        actualPrice = $(`#actualPrice-${line}`).html()
        actualPrice = actualPrice.replace('.', '');
        actualPrice = actualPrice.replace('$', '');
        actualPrice = parseFloat(actualPrice);


        unitsmanufacturated = $('#unitsmanufacturated').val();
        if (unitsmanufacturated == '') {
            toastr.error('Ingrese unidades a fabricar');
            return false;
        } else {
            // Calcular porcentaje
            percentage = 100 - ((negotiatePrice / actualPrice) * 100);
            $(`#percentage-${line}`).html(`${percentage.toFixed(3)} %`);

        }
    });

    /*    
    
        $(document).on('click keyup', `#negotiatePrice${i}`, function(e) {
            i = 0;
            negotiatePrice = this.value;
            //negotiatePrice = negotiatePrice.replace('.', '');
            negotiatePrice = parseFloat(negotiatePrice);
            unitsmanufacturated = $('#unitsmanufacturated').val();
            if (unitsmanufacturated == '') {
                toastr.error('Ingrese unidades a fabricar');
                return false;
            } else {
                debugger;
                // Calcular porcentaje
                percentage = 1 - r[0].cost / negotiatePrice;
            }
    
            this.value == '' ? (this.value = 0) : this.value;
        }); */
    /* }; */
});