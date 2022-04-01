$(document).ready(function() {
    /* Carga gasto total */
    $.ajax({
        type: 'POST',
        url: '/api/expenseTotal',
        success: function(r) {
            debugger
            $('#expensesToDistribution').val(r.total_expense);
            $('#expensesToDistribution').prop('disabled', true);
        },
    });
});