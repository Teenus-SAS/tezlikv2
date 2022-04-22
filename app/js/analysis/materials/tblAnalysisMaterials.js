const loadTableRawMaterialsAnalisys = (idProduct) => {
    $.ajax({
        type: 'GET',
        url: `/api/analysisRawMaterials/${idProduct}`,
        success: function(r) {
            debugger
            if (r.length == 0) {
                $('.col1').hide();
                $('.col2').hide();
                $('.empty').toggle(800);
                return false;
            } else {
                $('.empty').hide();
                $('.colMaterials').empty()
                for (i = 0; i < r.length; i++) {
                    $('.colMaterials').append(
                        `<tr class="col${i + 1}" id="col${i + 1}">
                        <th scope="row">1</th>
                        <th id="reference${i + 1}">${r[i].reference}</th>
                        <th id="rawMaterial${i + 1}">${r[i].material}</th>
                        <th id="actualPrice${i + 1}">${r[i].cost}</th>
                        <th><input class="form-control number" type="text" id="negotiatePrice${i + 1}"></th>
                        <th id="percentage${i + 1}"></th>
                        <th id="unityCost${i + 1}">${r[i].totalCost}</th>
                        <th id="monthCost${i + 1}"></th>
                        <th id="projectedCost${i + 1}"></th>
                    </tr>`
                    );
                }
            }
        },
    });
};