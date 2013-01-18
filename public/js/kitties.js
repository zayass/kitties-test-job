$(document).ready(function() {
    function rand(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function getKittyDimensions() {
        var width = 250,
            height = rand(200, 500);

        return {
            width: width,
            height: height
        };
    }

    function getKittyUrl (dimensions) {
        return "http://placekitten.com/" + dimensions.width + "/" + dimensions.height;
    }

    var $pin = $('.pin:first').clone();

    $('#columns').masonry({
        itemSelector: '.pin'
    });

    $('.load-kitties').click(function() {
        var $columns = $('#columns'),
            $pins = $([]);


        for (var i = 0; i < 12; i++) {
            var dimensions = getKittyDimensions();
            var currentPin = $pin.clone()
                .find('img').attr('src', getKittyUrl(dimensions)).css(dimensions)
                .end();

            $pins = $pins.add( currentPin.get() );
        }

        $columns.append($pins).masonry( 'appended', $pins);
    })


    $('.like-kitty').click(function() {
        var $this = $(this);

        $.post('/kitties', {
            width: $this.data('width'),
            height: $this.data('height')
        }, function(result) {
             if (result.success) {
                 $this.removeClass('btn-danger').addClass('btn-success');
             } else {
                 $this.removeClass('btn-success').addClass('btn-danger');
             }
        }).error(function () {
            $this.removeClass('btn-success').addClass('btn-danger');
        });
    })
})
