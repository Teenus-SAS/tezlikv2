$(document).ready(function () {
  /* Ocultar panel Nueva carga fabril */

  $('.cardFactoryLoad').hide();

  /* Abrir panel crear carga fabril */

  $('#btnNewFactoryLoad').click(function (e) {
    e.preventDefault();

    $('.cardFactoryLoad').toggle(800);
    $('#btnCreateFactoryLoad').html('Asignar');

    sessionStorage.removeItem('id_manufacturing_load');

    $('#idMachine option:contains(Seleccionar)').prop('selected', true);
    $('#descriptionFactoryLoad').val('');
    $('#cost').val('');
    $('#costMinute').val('');
  });

  /* Calcular Valor por minuto 
  $(document).on('click keyup', '#cost', function (e) {
    this.value == '' ? (this.value = 0) : this.value;

    let val = parseFloat(this.value) / 11200;
    $('#costMinute').val(val.toFixed());
  });*/

  /* Adicionar nueva carga fabril */

  $('#btnCreateFactoryLoad').click(function (e) {
    debugger;
    e.preventDefault();

    let idManufacturingLoad = sessionStorage.getItem('id_manufacturing_load');

    if (idManufacturingLoad == '' || idManufacturingLoad == null) {
      machine = parseInt($('#idMachine').val());
      description = $('#descriptionFactoryLoad').val();

      cost = parseInt($('#cost').val());
      //costMinute = parseInt($('#costMinute').val());

      data = machine * cost * costMinute;

      if (!data || description == '' || description == 0) {
        toastr.error('Ingrese todos los campos');
        return false;
      }

      factoryLoad = $('#formNewFactoryLoad').serialize();

      $.post(
        '../../api/addFactoryLoad',
        factoryLoad,
        function (data, textStatus, jqXHR) {
          message(data);
        }
      );
    } else {
      updateFactoryLoad();
    }
  });

  /* Actualizar carga fabril */

  $(document).on('click', '.updateFactoryLoad', function (e) {
    $('.cardFactoryLoad').show(800);
    $('#btnCreateFactoryLoad').html('Actualizar');

    let row = $(this).parent().parent()[0];
    let data = tblFactoryLoad.fnGetData(row);

    sessionStorage.setItem('id_manufacturing_load', data.id_manufacturing_load);

    $(`#idMachine option[value=${data.id_machine}]`).attr('selected', true);
    $('#descriptionFactoryLoad').val(data.input);
    $('#cost').val(data.cost);
    $('#valueMinute').val(data.cost_minute);

    $('html, body').animate(
      {
        scrollTop: 0,
      },
      1000
    );
  });

  updateFactoryLoad = () => {
    let data = $('#formNewFactoryLoad').serialize();
    idManufacturingLoad = sessionStorage.getItem('id_manufacturing_load');
    data = data + '&idManufacturingLoad=' + idManufacturingLoad;

    $.post(
      '../../api/updateFactoryLoad',
      data,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  };

  /* Eliminar carga fabril */

  $(document).on('click', '.deleteFactoryLoad', function (e) {
    let id_manufacturing_load = this.id;
    $.get(
      `../../api/deleteFactoryLoad/${id_manufacturing_load}`,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  });

  /* Mensaje de exito */

  message = (data) => {
    if (data.success == true) {
      $('.cardFactoryLoad').hide(800);
      $('#formNewFactoryLoad')[0].reset();
      updateTable();
      toastr.success(data.message);
      //return false
    } else if (data.error == true) toastr.error(data.message);
    else if (data.info == true) toastr.info(data.message);
  };

  /* Actualizar tabla */

  function updateTable() {
    $('#tblFactoryLoad').DataTable().clear();
    $('#tblFactoryLoad').DataTable().ajax.reload();
  }
});
