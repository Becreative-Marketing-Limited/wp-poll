/**
 * Front Script
 */

(function ($) {

    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/liquidpoll-widget-poll.default', function (scope, $) {

            let optionSingle = $('.liquidpoll-option-single');

            optionSingle.on('click', function () {
                optionSingle.removeClass('active');
                $(this).addClass('active');
            });

            $('.theme-6, .theme-7').on('click', function () {
                $(this).find('.liquidpoll-option-single input[name="submit_poll_option"]').not(':checked').prop("checked", true);
            });
        });
    });

})(jQuery);