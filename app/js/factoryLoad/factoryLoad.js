$(document).ready(function () {
  /* Ocultar panel Nueva carga fabril */

  $('.cardFactoryLoad').hide();
  $('#costMinute').prop('disabled', true);

  /* Abrir panel crear carga fabril */

  $('#btnNewFactoryLoad').click(function (e) {
    e.preventDefault();

    $('.cardFactoryLoad').toggle(800);
    $('#btnCreateFactoryLoad').html('Asignar');

    sessionStorage.removeItem('id_manufacturing_load');

    $('#idMachine option:contains(Seleccionar)').prop('selected', true);
    $('#descriptionFactoryLoad').val('');
    $('#costFactory').val('');
    $('#costMinute').val('');
  });

  /* Calcular Valor por minuto  */

  $(document).on('click keyup', '#costFactory', function (e) {
    machinesData = sessionStorage.getItem('machinesData');
    machinesData = JSON.parse(machinesData);

    idMachine = $('#idMachine').val();
    description = $('#descriptionFactoryLoad').val();

    if (idMachine == null || idMachine == '') {
      toastr.error('Seleccione la máquina');
      return false;
    }

    if (description == null || description == '') {
      toastr.error(
        'Ingrese la descripcion para la carga fabril de la maquina seleccionada'
      );
      return false;
    }

    for (let i = 0; i < machinesData.length; i++) {
      if (idMachine == machinesData[i]['id_machine']) {
        daysMachine = machinesData[i]['days_machine'];
        hoursMachine = machinesData[i]['hours_machine'];
        break;
      }
    }

    value = this.value / daysMachine / hoursMachine / 60;
    isNaN(value) ? (value = 0) : value;
    $('#costMinute').val(value.toFixed(2));
  });

  /* Adicionar nueva carga fabril */

  $('#btnCreateFactoryLoad').click(function (e) {
    e.preventDefault();

    let idManufacturingLoad = sessionStorage.getItem('id_manufacturing_load');

    if (idManufacturingLoad == '' || idManufacturingLoad == null) {
      valueMinute = parseInt($('#costMinute').val());

      if (valueMinute == '' || valueMinute == 0) {
        toastr.error('El costo de la carga fabril debe ser mayor a cero');
        return false;
      }

      $('#costMinute').prop('disabled', false);
      factoryLoad = $('#formNewFactoryLoad').serialize();

      $.post(
        '../../api/addFactoryLoad',
        factoryLoad,
        function (data, textStatus, jqXHR) {
          $('#costMinute').prop('disabled', true);
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
    $('#costFactory').val(data.cost);

    $('#costFactory').click();

    $('#valueMinute').val(data.cost_minute);

    $('html, body').animate(
      {
        scrollTop: 0,
      },
      1000
    );
  });

  updateFactoryLoad = () => {
    $('#costMinute').prop('disabled', false);
    let data = $('#formNewFactoryLoad').serialize();
    idManufacturingLoad = sessionStorage.getItem('id_manufacturing_load');
    data = data + '&idManufacturingLoad=' + idManufacturingLoad;

    $.post(
      '../../api/updateFactoryLoad',
      data,
      function (data, textStatus, jqXHR) {
        $('#costMinute').prop('disabled', true);
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
