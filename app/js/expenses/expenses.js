$(document).ready(function() {
    $('.cardCreateProduct').hide();

    $('#btnCreateProduct').click(function(e) {
        e.preventDefault();
        $('.cardCreateProduct').toggle(800);
    });


    $.ajax({
        type: "GET",
        url: "../../api/puc",
        success: function(r) {

            let $select1 = $(`#countNameExpenses`)
            $select1.empty()

            $select1.append(`<option disabled selected>Seleccionar</option>`)
            $.each(r, function(i, value) {
                $select1.append(
                    `<option value = ${value.id_puc}>${value.number_count} - ${value.count} </option>`,
                )
            })
        }
    });

    /* Seleccion producto */
    /* 
        $('#countNumberExpenses').change(function(e) {
            e.preventDefault();
            id = this.value
            $(`#countNameExpenses option[value=${id}]`).prop("selected", true);
            loadtableProcess(id)
        });

        $('#countNameExpenses').change(function(e) {
            e.preventDefault();
            id = this.value
            $(`#countNumberExpenses option[value=${id}]`).prop("selected", true);
            loadtableProcess(id)
        });
     */

});