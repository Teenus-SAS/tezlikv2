$(document).ready(function() {
    $('.cardCreateMachines').hide();

    $('#btnCreateMachine').click(function(e) {
        e.preventDefault();
        $('.cardCreateMachines').toggle(800);
    });
});