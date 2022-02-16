$(document).ready(function() {

    /* Cargue tabla de Máquinas */

    tblPrices = $('#tblPrices').dataTable({
        pageLength: 50,
        ajax: {
            url: '../../api/prices',
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
                data: 'reference',
                className: 'uniqueClassName',
            },
            {
                title: 'Producto',
                data: 'product',
                className: 'classCenter',
            },
            {
                title: 'Precio',
                data: "price",
                className: 'classCenter',
                render: $.fn.dataTable.render.number(".", ",", 0, "$ "),
            },
            {
                title: 'Img',
                data: "img",
                className: 'classCenter',
            },
            {
                title: 'Acciones',
                data: 'id_maquine',
                className: 'uniqueClassName',
                render: function(data) {
                    return `
                        <a href="javascript:;" onclick="loadContent('page-content','views/analysis/detailsPrices.php')" <i id="${data}" class="mdi mdi-playlist-check seeDetail" data-toggle='tooltip' title='Ver Detalle' style="font-size: 30px;"></i></a>`
                },
            },
        ],
    })
});