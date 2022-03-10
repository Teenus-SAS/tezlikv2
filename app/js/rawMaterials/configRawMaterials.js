$(document).ready(function() {
    $('.cardRawMaterials').hide();

    $('#btnNewMaterial').click(function(e) {
        e.preventDefault();
        $('.cardRawMaterials').toggle(800);
    });


    $.ajax({
        type: "GET",
        url: "../../api/materials",
        success: function(r) {

            let $select = $(`#nameRawMaterial`)
            $select.empty()

            $select.append(`<option disabled selected>Seleccionar</option>`)
            $.each(r, function(i, value) {
                $select.append(
                    `<option value = ${value.id_material}> ${value.material} </option>`,
                )
            })

            // let $select1 = $(`#selectNameProduct`)
            // $select1.empty()

            // $select1.append(`<option disabled selected>Seleccionar</option>`)
            // $.each(r, function(i, value) {
            //     $select1.append(
            //         `<option value = ${value.id_material}> ${value.product} </option>`,
            //     )
            // })
        }
    });



});