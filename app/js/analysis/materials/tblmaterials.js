$(document).ready(function () {
  /* Seleccion producto */
  $('#refProduct').change(function (e) {
    e.preventDefault();
    id = this.value;

    $(`#selectNameProduct option[value=${id}]`).prop('selected', true);
    loadtableMaterials(id);
    loadTableRawMaterialsAnalisys(id);

    /*$('#btnComposition').prop('disabled', true);
        $('.cardTableRawMaterials').toggle(800);*/
  });

  $('#selectNameProduct').change(function (e) {
    e.preventDefault();
    id = this.value;

    $(`#refProduct option[value=${id}]`).prop('selected', true);
    loadtableMaterials(id);
    loadTableRawMaterialsAnalisys(id);
  });

  /* Cargue tabla de Proyectos */
  const loadtableMaterials = (idProduct) => {
    tblMaterials = $('#tblMaterials').dataTable({
      destroy: true,
      pageLength: 50,
      ajax: {
        url: `/api/rawMaterials/${idProduct}`,
        dataSrc: '',
      },
      language: {
        url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json',
      },
      columns: [
        {
          title: 'No.',
          data: null,
          className: 'uniqueClassName',
          render: function (data, type, full, meta) {
            return meta.row + 1;
          },
        },
        {
          title: 'Referencia',
          data: 'reference',
          className: 'uniqueClassName',
        },
        {
          title: 'Materia Prima',
          data: 'material',
          className: 'uniqueClassName',
        },
        {
          title: 'Precio Total',
          data: 'unityCost',
          className: 'classCenter',
          render: $.fn.dataTable.render.number('.', ',', 0, '$ '),
        },
        {
          title: 'Participacion',
          data: 'participation',
          className: 'classCenter',
          render: $.fn.dataTable.render.number(',', '.', 2, '', '%'),
        },
      ],

      footerCallback: function (row, data, start, end, display) {
        total = this.api()
          .column(3)
          .data()
          .reduce(function (a, b) {
            return parseInt(a) + parseInt(b);
          }, 0);

        $(this.api().column(3).footer()).html(
          new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
          }).format(total)
        );
        subTotal = this.api()
          .column(4)
          .data()
          .reduce(function (a, b) {
            return a + b;
          }, 0);

        $(this.api().column(4).footer()).html(`${subTotal} %`);
      },
    });
  };
});
