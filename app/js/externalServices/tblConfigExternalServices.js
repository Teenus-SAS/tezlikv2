$(document).ready(function() {

    /* Seleccion producto */

    $('#refProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#selectNameProduct option[value=${id}]`).attr("selected", true);
        loadtableExternalServices(id)
    });

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#refProduct option[value=${id}]`).attr("selected", true);
        loadtableExternalServices(id)
    });


    /* Cargue tabla de Proyectos */

    const loadtableExternalServices = (idProduct) => {

        tblConfigExternalServices = $('#tblConfigExternalServices').dataTable({
            destroy: true,
            pageLength: 50,
            ajax: {
                url: `../../api/externalservices/${idProduct}`,
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
                    title: 'Servicio',
                    data: 'nombre_servicio'
                },
                {
                    title: 'Costo',
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
                        <a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateProducts" data-toggle='tooltip' title='Actualizar Producto' style="font-size: 30px;"></i></a>    
                        <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteMateriales" data-toggle='tooltip' title='Eliminar Materia Prima' style="font-size: 30px;color:red"></i></a>`
                    },
                },
            ],
        })
    }






});