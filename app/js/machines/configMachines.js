$(document).ready(function () {
  $(".cardCreateMachines").hide();

  $("#btnNewMachine").click(function (e) {
    e.preventDefault();
    $(".cardCreateMachines").toggle(800);
  });

  $.ajax({
    type: "GET",
    url: "../../api/machines",
    success: function (r) {
      let $select = $(`#idMachine`);
      $select.empty();

      $select.append(`<option disabled selected>Seleccionar</option>`);
      $.each(r, function (i, value) {
        $select.append(
          `<option value = ${value.id_machine}> ${value.machine} </option>`
        );
      });

      // let $select1 = $(`#selectNameProduct`)
      // $select1.empty()

      // $select1.append(`<option disabled selected>Seleccionar</option>`)
      // $.each(r, function(i, value) {
      //     $select1.append(
      //         `<option value = ${value.id_material}> ${value.product} </option>`,
      //     )
      // })
    },
  });
});