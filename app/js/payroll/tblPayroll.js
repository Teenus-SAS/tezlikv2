$(document).ready(function() {

    /* Cargue tabla de Máquinas */

    tblPayroll = $('#tblPayroll').dataTable({
        pageLength: 50,
        ajax: {
            url: '../../api/payroll',
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
                title: 'Nombre Empleado',
                data: 'nombre_empleado'
            },
            {
                title: 'Proceso',
                data: 'proceso'
            },
            {
                title: 'Salario Base',
                data: "salario",
                className: 'classRight',
                render: $.fn.dataTable.render.number(".", ",", 0, "$ "),
            },
            {
                title: 'Salario Neto',
                data: "salario_neto",
                className: 'classRight',
                render: $.fn.dataTable.render.number(".", ",", 0, "$ "),
            },
            {
                title: 'Acciones',
                data: 'id_nominas',
                className: 'uniqueClassName',
                render: function(data) {
                    return `
                <a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateProducts" data-toggle='tooltip' title='Actualizar Producto' style="font-size: 30px;"></i></a>
                <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteProducts" data-toggle='tooltip' title='Eliminar Producto' style="font-size: 30px;color:red"></i></a>`
                },
            },
        ],
    })
});