$(document).ready(function() {
    $('.cardCreateProcess').hide();

    $('#btnNewProcess').click(function(e) {
        e.preventDefault();
        $('.cardCreateProcess').toggle(800);
    });


    $.ajax({
        type: "GET",
        url: "../../api/materials",
        success: function(r) {

            let $select = $(`#process`)
            $select.empty()

            $select.append(`<option disabled selected>Seleccionar</option>`)
            $.each(r, function(i, value) {
                $select.append(
                    `<option value = ${value.id_process}> ${value.process} </option>`,
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