$(document).ready(function() {
    var loginErrorElement = null;

    $('.sign-in').click(function() {
        loginErrorElement = $('<ul><li></li></ul>').prependTo( $('#modal-login dl') ).find('li');

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


    function updateModal() {
        var modal = $('#modal-login');
        $.modal.update(modal.height() + 10, modal.width());
    }


    $('#modal-login form').submit(function () {
        var $this = $(this);

        $.post('/user/ajax-login', $this.serialize() , function(response) {
            if (response.success) {
                location.reload();
            } else {
                loginErrorElement.text(response.message);
                updateModal();
            }
        });

        return false;
    });
});
