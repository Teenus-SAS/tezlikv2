$(document).ready(function() {
    $('.cardCreateProcess').hide();

    $('#btnCreateProcess').click(function(e) {
        e.preventDefault();
        $('.cardCreateProcess').toggle(800);
    });
});