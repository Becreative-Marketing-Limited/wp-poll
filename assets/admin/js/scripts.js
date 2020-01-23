/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {
        $(".poll-options").sortable({handle: ".option-move", revert: true});
        $(".poll_option_container").sortable({handle: ".poll_option_single_sorter"});
    });


    $(document).on('change', '#poll_style_countdown, #poll_options_theme, #poll_animation_checkbox, #poll_animation_radio', function () {

        let selectedOption = $(this).find('option:selected').val(),
            thisOption = $(this).parent().parent(),
            thisPreviewLink = thisOption.find('.wpp-preview-link'),
            demoServer = thisPreviewLink.data('demo-server'),
            target = thisPreviewLink.data('target'),
            finalURL = '';

        if (typeof selectedOption === 'undefined' || selectedOption.length === 0) {
            return;
        }

        finalURL = 'https:' + demoServer + '/' + target + '-' + selectedOption;

        thisPreviewLink.attr('href', finalURL);
    });


    $(document).on('click', 'span.shortcode', function () {

        let inputField = document.createElement('input'),
            htmlElement = $(this),
            ariaLabel = htmlElement.attr('aria-label');

        document.body.appendChild(inputField);
        inputField.value = htmlElement.html();
        inputField.select();
        document.execCommand('copy', false);
        inputField.remove();

        htmlElement.attr('aria-label', pluginObject.copyText);

        setTimeout(function () {
            htmlElement.attr('aria-label', ariaLabel);
        }, 5000);
    });


    $(document).on('change', '#wpp_reports_style', function () {


        let parts = location.search.replace('?', '').split('&').reduce(function (s, c) {
                var t = c.split('=');
                s[t[0]] = t[1];
                return s;
            }, {}),
            redirectURL = window.location.protocol + '//' + window.location.hostname + window.location.pathname + '?',
            styleType = $(this).find('option:selected').val();

        $.each(parts, function (index, value) {
            redirectURL += index + '=' + value + '&';
        });

        if (typeof styleType === 'undefined' || styleType.length === 0) {
            window.location.replace(redirectURL);
        }

        redirectURL += 'type=' + styleType;

        window.location.replace(redirectURL);
    });

    $(document).on('change', '#wpp_reports_poll_id', function () {


        let parts = location.search.replace('?', '').split('&').reduce(function (s, c) {
                var t = c.split('=');
                s[t[0]] = t[1];
                return s;
            }, {}),
            redirectURL = window.location.protocol + '//' + window.location.hostname + window.location.pathname + '?',
            pollID = $(this).find('option:selected').val();

        $.each(parts, function (index, value) {
            redirectURL += index + '=' + value + '&';
        });

        if (typeof pollID === 'undefined' || pollID.length === 0) {
            window.location.replace(redirectURL);
        }

        redirectURL += 'poll-id=' + pollID;

        window.location.replace(redirectURL);
    });


    /**
     * Add new option in poll meta box
     */
    $(document).on('click', '.wpp-add-poll-option', function () {

        console.log( $(this).data('poll-id') );

        $.ajax({
            type: 'GET',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                "action": "wpp_ajax_add_option",
                "poll_id": $(this).data('poll-id'),
            },
            success: function (response) {

                if (response.success) {
                    $(response.data).hide().appendTo('.poll-options').slideDown();
                }
            }
        });
    });


    /**
     * Remove option in poll meta box
     */
    $(document).on('click', 'span.option-remove', function () {

        let status = $(this).data('status'), buttonRemove = $(this), pollOption = $(this).parent().parent();

        if (status === 0) {
            buttonRemove.data('status', 1).html('<i class="icofont-check"></i>');
        } else {
            pollOption.slideUp(500, function () {
                pollOption.remove();
            });

        }
    });


})(jQuery, window, document, wpp_object);







