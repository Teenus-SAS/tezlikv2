$(document).ready(function() {

    /* Cargue tabla de Gastos distribuidos */

    tblExpensesDistribution = $('#tblExpensesDistribution').dataTable({
        destroy: true,
        pageLength: 50,
        ajax: {
            url: `../../api/expensesDistribution`,
            dataSrc: '',
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json',
        },
        columns: [{
                title: 'No.',
                "data": null,
                className: 'uniqueClassName',
                "render": function(data, type, full, meta) {
                    return meta.row + 1;
                }
            },
            {
                title: 'Referencia',
                data: 'ref'
            },
            {
                title: 'Producto',
                data: "producto",
            },
            {
                title: 'Unidades Vendidas',
                data: "unidades_vendidas",
                className: 'classRight',
            },
            {
                title: 'Volumen de Ventas',
                data: "volumen_ventas",
                className: 'classRight',
            },
            {
                title: 'Gasto Asignable al Producto',
                data: "gasto_asignable",
                className: 'classRight',
            },
            {
                title: 'Acciones',
                data: 'id_materiales',
                className: 'uniqueClassName',
                render: function(data) {
                    return `
                        <a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateProducts" data-toggle='tooltip' title='Actualizar Producto' style="font-size: 30px;"></i></a>    
                        <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteMateriales" data-toggle='tooltip' title='Eliminar Materia Prima' style="font-size: 30px;color:red"></i></a>`
                },
            },
        ],
    })







});