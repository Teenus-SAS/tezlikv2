$(document).ready(function () {
  $.ajax({
    type: 'GET',
    url: '../../api/payroll',
    success: function (r) {
      payrollData = JSON.stringify(r);
      sessionStorage.setItem('payrollData', payrollData);
    },
  });
});
