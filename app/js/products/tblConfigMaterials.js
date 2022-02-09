$(document).ready(function() {

    /* Seleccion producto */

    $('#refProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#selectNameProduct option[value=${id}]`).attr("selected", true);
        loadtableMaterials(id)
    });

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#refProduct option[value=${id}]`).attr("selected", true);
        loadtableMaterials(id)
    });


    /* Cargue tabla de Proyectos */

    const loadtableMaterials = (idProduct) => {

        tblConfigProducts = $('#tblConfigProducts').dataTable({
            destroy: true,
            pageLength: 50,
            ajax: {
                url: `../../api/productsmaterials/${idProduct}`,
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
                    data: 'referencia',
                    className: 'uniqueClassName',
                },
                {
                    title: 'Producto',
                    data: 'descripcion',
                    className: 'uniqueClassName',
                },
                {
                    title: 'Unidad',
                    data: "unidad",
                    className: 'classCenter'
                },
                {
                    title: 'Precio',
                    data: "costo",
                    className: 'classRight',
                    render: $.fn.dataTable.render.number(".", ",", 0, "$ "),
                },
                {
                    title: 'Acciones',
                    data: 'id_materiales',
                    className: 'uniqueClassName',
                    render: function(data) {
                        return `
                            <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteMateriales" data-toggle='tooltip' title='Eliminar Materia Prima' style="font-size: 30px;color:red"></i></a>`
                    },
                },
            ],
        })
    }






});