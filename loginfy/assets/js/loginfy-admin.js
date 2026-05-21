(function($) {
    // Notice Hide
    $('body').on('click', '.loginfy-upgrade-popup .popup-dismiss', function(evt) {
        evt.preventDefault();
        $(this).closest('.loginfy-upgrade-popup').fadeOut(200);
    });

    // Notice Show
    $('body').on('click', '.loginfy-upgrade-pro', function(evt) {
        evt.preventDefault();
        $('.loginfy-upgrade-popup').fadeIn(200);
    });
})(jQuery);
