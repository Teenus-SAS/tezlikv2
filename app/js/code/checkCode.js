$(document).ready(function () {
  $('#btnCheckCode').click(function (e) {
    e.preventDefault();
    code = $('#code').val();

    if (code == '' || code == null) {
      toastr.error('Ingrese codigo');
      return false;
    }

    checkCode = $('#formCheckCode').serialize();

    $.post(
      '../../api/checkCode',
      checkCode,
      function (data, textStatus, jqXHR) {
        message(data);
      }
    );
  });
});
