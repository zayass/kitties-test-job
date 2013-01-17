$(document).ready(function() {
    $('.sign-in').click(function() {
        $('#modal-login').modal({
            opacity: 80,
            overlayClose: true,
            overlayCss: {
                backgroundColor:"#000"
            },
            containerCss: {
                backgroundColor:"#fff",
                padding: 30,
                borderRadius: 7
            }
        });

        return false;
    });
});
