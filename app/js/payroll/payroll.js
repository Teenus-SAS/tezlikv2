$(document).ready(function() {
    $('.cardCreatePayroll').hide();

    $('#btnCreatePayroll').click(function(e) {
        e.preventDefault();
        $('.cardCreatePayroll').toggle(800);
    });
});