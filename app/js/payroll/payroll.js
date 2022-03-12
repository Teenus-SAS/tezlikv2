$(document).ready(function() {
    $('.cardCreatePayroll').hide();

    $('#btnCreatePayroll').click(function(e) {
        e.preventDefault();
        $('#createPayroll').modal('show');
    });
});