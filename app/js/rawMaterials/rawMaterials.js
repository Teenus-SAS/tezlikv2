$(document).ready(function() {
    $('.cardCreateRawMaterials').hide();

    $('#btnCreateRawMaterials').click(function(e) {
        e.preventDefault();
        $('.cardCreateRawMaterials').toggle(800);
    });
});