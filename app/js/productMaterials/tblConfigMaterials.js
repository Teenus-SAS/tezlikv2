$(document).ready(function() {

    /* Seleccion producto */

    $('#refProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#selectNameProduct option[value=${id}]`).prop("selected", true);
        loadtableMaterials(id)
    });

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#refProduct option[value=${id}]`).prop("selected", true);
        loadtableMaterials(id)
    });

    /* Seleccion materia */

    $('#refMaterial').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#nameRawMaterial option[value=${id}]`).prop("selected", true);
        loadtableMaterials(id)
    });

    /* Cargue tabla de Proyectos */

    const loadtableMaterials = (idProduct) => {

        tblConfigMaterials = $('#tblConfigMaterials').dataTable({
            destroy: true,
            pageLength: 50,
            ajax: {
                url: `../../api/productsMaterials/${idProduct}`,
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
                    data: "referencia",
                    className: 'uniqueClassName',
                },
                {
                    title: 'Producto',
                    data: "descripcion",
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
                    data: 'id_product_material',
                    className: 'uniqueClassName',
                    render: function(data) {
                        return `
                        <a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateMaterials" data-toggle='tooltip' title='Actualizar Proceso' style="font-size: 30px;"></i></a>
                        <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteMaterials" data-toggle='tooltip' title='Eliminar Materia Prima' style="font-size: 30px;color:red"></i></a>`
                    },
                },
            ],
        })
    }






});