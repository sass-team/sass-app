jQuery(document).ready(function () {

    /*
     Background slideshow
     */
    $('.coming-soon').backstretch([
        "assets/img/backgrounds/1.jpg", "assets/img/backgrounds/2.jpg", "assets/img/backgrounds/3.jpg"
    ], {duration: 3000, fade: 750});

    /*
     Countdown initializer
     */
    var now = new Date();
    // var countTo = 25 * 24 * 60 * 60 * 1000 + now.valueOf();
    var countTo = "2014/10/06 10:00";
    $('.timer').countdown(countTo, function (event) {
        var $this = $(this);
        switch (event.type) {
            case "seconds":
            case "minutes":
            case "hours":
            case "days":
            case "weeks":
            case "daysLeft":
                $this.find('span.' + event.type).html(event.value);
                break;
            case "finished":
                $this.hide();
                break;
        }
    });

    /*
     Tooltips
     */
    $('.social a.facebook').tooltip();
    $('.social a.twitter').tooltip();
    $('.social a.dribbble').tooltip();
    $('.social a.googleplus').tooltip();
    $('.social a.pinterest').tooltip();
    $('.social a.flickr').tooltip();

    /*
     Subscription form
     */
    $('.success-message').hide();
    $('.error-message').hide();

    $('.subscribe form').submit(function () {
        var postdata = $('.subscribe form').serialize();
        $.ajax({
            type: 'POST',
            url: 'assets/sendmail.php',
            data: postdata,
            dataType: 'json',
            success: function (json) {
                if (json.valid == 0) {
                    $('.success-message').hide();
                    $('.error-message').hide();
                    $('.error-message').html(json.message);
                    $('.error-message').fadeIn();
                }
                else {
                    $('.error-message').hide();
                    $('.success-message').hide();
                    $('.subscribe form').hide();
                    $('.success-message').html(json.message);
                    $('.success-message').fadeIn();
                }
            }
        });
        return false;
    });

});

