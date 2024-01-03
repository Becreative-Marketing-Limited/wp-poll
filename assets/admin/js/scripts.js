/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {
        $(".poll-options").sortable({handle: ".option-move", revert: true});
        $('.liquidpoll-reaction-options .pbsettings-siblings').sortable({handle: '.sortable', revert: true});
        $(".poll_option_container").sortable({handle: ".poll_option_single_sorter"});

        let date_picker_1 = $('.liquidpoll-sort-form input[name="date_1"]'),
            date_picker_2 = $('.liquidpoll-sort-form input[name="date_2"]');

        if (date_picker_1.length > 0) {
            date_picker_1.datepicker({
                dateFormat: "yy-mm-dd"
            });
        }

        if (date_picker_2.length > 0) {
            date_picker_2.datepicker({
                dateFormat: "yy-mm-dd"
            });
        }
    });

    $(function () {
        let el_sortable = $(".wpdk_settings--image-group");
        el_sortable.sortable();
        el_sortable.disableSelection();
    });


    $(document).on('change', '.liquidpoll-sort-form select[name="date"]', function () {
        let this_selection = $(this),
            date_picker_1 = $('.liquidpoll-sort-form input[name="date_1"]'),
            date_picker_2 = $('.liquidpoll-sort-form input[name="date_2"]'),
            date_selected = this_selection.val();

        if ('custom' === date_selected) {
            date_picker_1.fadeIn(100);
            date_picker_2.fadeIn(100);
        } else {
            date_picker_1.val('').fadeOut(100);
            date_picker_2.val('').fadeOut(100);
        }

        // $('.liquidpoll-export-form input[name="date"]').val(value_id);
    });


    $(document).on('change', '.liquidpoll-sort-form select[name="type"]', function () {

        let this_selection = $(this),
            this_selection_target = this_selection.parent().parent().find('select[name="object"]'),
            poll_type = this_selection.val();

        $('.liquidpoll-export-form input[name="type"]').val(poll_type);

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_get_polls',
                'poll_type': poll_type,
            },
            success: function (response) {
                if (response.success) {
                    this_selection_target.html(response.data);
                }
            }
        });
    });


    $(document).on('change', '.liquidpoll-sort-form select[name="object"]', function () {

        let this_selection = $(this),
            this_selection_target = this_selection.parent().parent().find('select[name="value"]'),
            object_id = this_selection.val();

        $('.liquidpoll-export-form input[name="object"]').val(object_id);

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_get_option_values',
                'object_id': object_id,
            },
            success: function (response) {
                if (response.success) {
                    this_selection_target.html(response.data);
                }
            }
        });
    });


    $(document).on('change', '.liquidpoll-sort-form select[name="value"]', function () {
        let this_selection = $(this),
            value_id = this_selection.val();

        $('.liquidpoll-export-form input[name="value"]').val(value_id);
    });


    $(document).on('change', '#liquidpoll-poll-selection', function () {
        let pollSelectionField = $(this),
            pollSelectionFieldValue = pollSelectionField.val(),
            currentPageURL = pollSelectionField.data('url');

        window.onbeforeunload = null;
        window.location.href = currentPageURL + '&poll-id=' + pollSelectionFieldValue + '#tab=reports';
    });


    $(window).on('load', function () {
        let updateContainer = $('#wp-poll-pro-update'),
            detailsButton = updateContainer.find('.thickbox.open-plugin-details-modal');

        detailsButton.removeClass('thickbox').attr('target', '_blank').attr('href', pluginObject.tempProDownload).html(pluginObject.tempProDownloadTxt);
    });


    $(document).on('change', 'input[name="liquidpoll_poll_meta[_type]"]', function () {

        console.log($(this).val());
    });


    $(document).on('click', '.liquidpoll-poll-meta .meta-nav > li', function () {

        let thisMetaNav = $(this),
            target = thisMetaNav.data('target'),
            metaContent = thisMetaNav.parent().parent().parent().find('.meta-content');

        if (thisMetaNav.hasClass('active')) {
            return;
        }

        metaContent.addClass('loading');
        metaContent.find('.tab-content-item').removeClass('active');

        thisMetaNav.parent().find('.active').removeClass('active');
        thisMetaNav.addClass('active');

        setTimeout(function () {
            metaContent.find('.' + target).addClass('active');
            metaContent.removeClass('loading');
        }, 500);
    });


    $(document).on('change', '#poll_style_countdown, #poll_options_theme, #poll_animation_checkbox, #poll_animation_radio', function () {

        let selectedOption = $(this).find('option:selected').val(),
            thisOption = $(this).parent().parent(),
            thisPreviewLink = thisOption.find('.liquidpoll-preview-link'),
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


    $(document).on('change', '#liquidpoll_reports_style', function () {


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


    $(document).on('change', '#liquidpoll_reports_poll_id', function () {


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


    $(document).on('click', '.liquidpoll-add-poll-option', function () {

        console.log($(this).data('poll-id'));

        $.ajax({
            type: 'GET',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                "action": "liquidpoll_ajax_add_option",
                "poll_id": $(this).data('poll-id'),
            },
            success: function (response) {

                if (response.success) {
                    $(response.data).hide().appendTo('.poll-options').slideDown();
                }
            }
        });
    });


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


    $(document).on('click', '.liquidpoll-activate-addon', function () {

        let addOnID = $(this).data('addon-id'),
            addOnNonce = $(this).data('addon-nonce'),
            nonceName = $(this).data('addon-nonce-name');

        if (typeof addOnID === 'undefined') {
            return;
        }

        let loader = $('.loader');

        $(this).html('Activating').append(loader)
        loader.css('display', 'inline-block')

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll-activate-addon',
                'addon_id': addOnID,
                'addon_nonce': addOnNonce,
                'addon_nonce_name': nonceName,
            },
            success: function (response) {
                if (response.success) {
                    $(this).removeClass('liquidpoll-activate-addon').addClass('active').removeAttr('data-addon-id');
                    loader.css('display', 'none')
                    $(this).parent().parent().parent().append(loader)
                    $(this).text("Active");
                }
            }
        });
    });

})(jQuery, window, document, liquidpoll_object);







