$(document).ready(function() {
    $('.cardImportProducts').hide();

    $('#btnImportProducts').click(function(e) {
        e.preventDefault();
        $('.cardCreateProduct').hide(800);
        $('.cardImportProducts').toggle(800);




    });
});