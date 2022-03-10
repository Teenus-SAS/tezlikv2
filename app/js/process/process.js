
$(document).ready(function () {

    /* Ocultar panel crear producto */

    $('.cardCreateProcess').hide();

    /* Abrir panel crear producto */

    $('#btnNewProcess').click(function(e) {
        e.preventDefault();

        $('.cardCreateProcess').toggle(800);
        $('#btnCreateProcess').html('Crear Proceso');
         
        $('#process').val('');

    });

    /* Crear nuevo proceso */

    $('#btnCreateProcess').click(function(e){
        e.preventDefault();
        let idProcess = sessionStorage.getItem('id_process')

        if(idProcess == '') {
            proce = $('#process').val('');

            if(proce == '' || proce == 0)
            {
                toastr.error('Ingrese todos los campos')
                return false
            }

            process = $('#formCreateProcess').serialize();

            $.post("../../../api/addProcess", process,
                function(data, textStatus, jqXHR) {
                    message(data)
                },
            );
        } else {
            updateProcess();
        }
    });

    /* Actualizar procesos */

    $(document).on('click', '.updateProcess', function(e) {

        $('.cardCreateProcess').shows(800);
        $('#btnCreateProcess').html('Actualizar Proceso');

        let row = $(this).parent().parent()[0]
        let data = tblProcess.fnGetData(row)

        sessionStorage.setItem('id_process', data.id_process)

        $('#process').val(data.process);

        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    });

    updateProcess = () => {
        let data = $('#formCreateProcess').serialize();
        $.post("../../../api/updateProcess", process,
            function(data, textStatus, jqXHR) {
                message(data)
            },
        );
    }

    /* Eliminar proceso */

    $(document).on('click', '.deleteProcess', function(e) {
        let id_process = this.id
        $.get(`../../../api/deleteProcess/${id_process}`,
            function(data, textStatus, jqXHR){
                message(data)
            }  
        )
    });

    /* Mensaje de exito */

    message = (data) => {
        if (data.success == true) {
            $('.cardCreateProcess').hide(800);
            $("#formCreateProcess")[0].reset();
            updateTable()
            toastr.success(data.message)
            //return false
        } else if (data.error == true)
            toastr.error(data.message)
        else if (data.info == true)
            toastr.info(data.message)
    }

    /* Actualizar tabla */

    function updateTable() {
        $('#tblProcess').DataTable().clear()
        $('#tblProcess').DataTable().ajax.reload()
    }
});