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

            let $select = $(`#material`)
            $select.empty()
            debugger
            $select.append(`<option disabled selected>Seleccionar</option>`)
            $.each(r, function(i, value) {
                $select.append(
                    `<option value = ${value.id_material}> ${value.material} </option>`,
                )
            })
        }
    });



});