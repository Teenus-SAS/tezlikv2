$(document).ready(function() {
    if (intentos > 5) {
        setTimeout(() => {
            $.ajax({
                url: "/api/delay",
                success: function(response) {
                    location.reload();
                },
            });
        }, 60000);
    }
});