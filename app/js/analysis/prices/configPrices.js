$(document).ready(function() {
    $(document).on('click', '.seeDetail', function(e) {
        let id_product = this.id;
        sessionStorage.setItem('idProduct', id_product)
    });
});