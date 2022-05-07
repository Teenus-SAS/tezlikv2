const sendCode = () => {
  $.get('../api/sendEmail', function (data, textStatus, jqXHR) {
    debugger;
    if (data.success)
      location.href = '../../app/views/login/loginDoubleFactor.php';
    else toastr.error('Error al enviar email, vuelva a intertar');
  });
};
/*$('#btnCheckCode').click(function (e) {
    e.preventDefault();
    debugger;
    code = $('#factor').val();

    if (code == '' || code == null) {
      toastr.error('Ingrese codigo');
      return false;
    }

    checkCode = $('#loginForm').serialize();

    $.post(
      '../../api/checkCode',
      checkCode,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  });*/
