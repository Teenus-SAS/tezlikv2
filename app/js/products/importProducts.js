$(document).ready(function() {
    let selectedFile;

    $('.cardImportProducts').hide();

    $('#btnNewImportProducts').click(function(e) {
        e.preventDefault();
        $('.cardCreateProduct').hide(800);
        $('.cardImportProducts').toggle(800);
    });

    $('#fileProducts').change(function(e) {
        e.preventDefault();
        selectedFile = e.target.files[0];
    });

    $('#btnImportProducts').click(function(e) {
        e.preventDefault();

        file = $('#fileProducts').val();

        if (!file) {
            toastr.error('Seleccione un archivo');
            return false
        }

        importFile(selectedFile)
    });

});