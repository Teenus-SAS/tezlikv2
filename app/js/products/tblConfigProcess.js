$(document).ready(function() {

    /* Seleccion producto */

    $('#refProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#selectNameProduct option[value=${id}]`).attr("selected", true);
        loadtableProcess(id)
    });

    $('#selectNameProduct').change(function(e) {
        e.preventDefault();
        id = this.value
        $(`#refProduct option[value=${id}]`).attr("selected", true);
        loadtableProcess(id)
    });


    /* Cargue tabla de Proyectos */

    const loadtableProcess = (idProduct) => {

        tblConfigProcess = $('#tblConfigProcess').dataTable({
            destroy: true,
            pageLength: 50,
            ajax: {
                url: `../../api/productshasprocess/${idProduct}`,
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
                    title: 'Proceso',
                    data: 'proceso'
                },
                {
                    title: 'Máquina',
                    data: "maquina"
                },
                {
                    title: 'Tiempo Alistamiento',
                    data: "tiempo_alistamiento",
                    className: 'uniqueClassName',
                },
                {
                    title: 'Tiempo Operación',
                    data: "tiempo_operacion",
                    className: 'uniqueClassName',
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