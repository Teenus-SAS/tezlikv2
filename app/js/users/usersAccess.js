$(document).ready(function () {
  /* ACCESO DE USUARIO */
  $.ajax({
    type: 'POST',
    url: `/api/userAccess`,
    success: function (resp) {
      console.log(resp);
      //   $('#expensesToDistribution').val(`$ ${r.total_expense.toLocaleString()}`);
      //   $('#expensesToDistribution').prop('disabled', true);
    },
  });
});
