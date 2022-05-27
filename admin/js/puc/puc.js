$(document).ready(function () {
  /* Cargue tabla PUC */

  tblPUC = $('#tblPUC').dataTable({
    destroy: true,
    pageLength: 50,
    ajax: {
      url: `/api/puc`,
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
        title: 'NIT',
        data: 'number_count',
      },
      {
        title: 'Empresa',
        data: 'count',
      },
      {
        title: 'Inicio Licencia',
        data: 'number_count',
      },
      {
        title: 'Final Licencia',
        data: 'number_count',
      },
      {
        title: 'Días de Licencia',
        data: 'number_count',
      },
      {
        title: 'Cant. Usuarios',
        data: 'number_count',
      },
      {
        title: 'Estado',
        data: 'number_count',
      },
      //   {
      //     title: 'Valor',
      //     data: 'expense_value',
      //     className: 'classRight',
      //     render: $.fn.dataTable.render.number('.', ',', 0, '$ '),
      //   },
      {
        title: 'Acciones',
        data: 'id_expense',
        className: 'uniqueClassName',
        render: function (data) {
          return `
                  <a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateExpenses" data-toggle='tooltip' title='Actualizar Gasto' style="font-size: 30px;"></i></a>
                  <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteExpenses" data-toggle='tooltip' title='Eliminar Gasto' style="font-size: 30px;color:red"></i></a>`;
        },
      },
    ],
  });
});
