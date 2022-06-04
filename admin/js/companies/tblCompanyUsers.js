$(document).ready(function() {
    /* Cargue tabla Usuarios Empresa */

    let idCompany = sessionStorage.getItem('id_company')

    tblCompanyUsers = $('#tblCompanyUsers').dataTable({
        pageLength: 50,
        ajax: {
            url: `/api/companyUsers/${idCompany}`,
            dataSrc: '',
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json',
        },
        columns: [{
                title: 'No.',
                data: null,
                className: 'uniqueClassName',
                render: function(data, type, full, meta) {
                    return meta.row + 1;
                },
            },
            {
                title: 'NIT',
                data: 'nit',
            },
            {
                title: 'Empresa',
                data: 'company',
            },
            {
                title: 'Inicio Licencia',
                data: 'license_start',
            },
            {
                title: 'Final Licencia',
                data: 'license_end',
            },
            {
                title: 'Días de Licencia',
                data: 'license_days',
            },
            {
                title: 'Cant. Usuarios',
                data: 'quantity_user',
            },
            {
                title: 'Acciones',
                data: 'id_company',
                className: 'uniqueClassName',
                render: function(data) {
                    return `<a href="javascript:;" <i id="${data}" class="bx bx-edit-alt updateLicenses" data-toggle='tooltip' title='Actualizar Licencia' style="font-size: 30px;"></i></a>`;
                },
                // <a href="javascript:;" <i id="${data}" class="mdi mdi-delete-forever deleteExpenses" data-toggle='tooltip' title='Eliminar Gasto' style="font-size: 30px;color:red"></i></a>`;
            },
        ],
    });
});