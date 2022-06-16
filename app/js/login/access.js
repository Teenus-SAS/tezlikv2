$(document).ready(function() {
    delay = () => {
        $.ajax({
            url: "/api/delay",
            success: function(resp) {
                location.reload();
            },
        });
    }
});