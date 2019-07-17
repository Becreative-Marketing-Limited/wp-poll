/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";


    $(document).on('click', '.wpp-new-option > button', function () {

        let popupBoxContainer = $(this).parent().parent().parent(),
            pollID = $(this).data('pollid'),
            optionField = $(this).parent().find('input[type="text"]'),
            optionValue = optionField.val();

        if (typeof pollID === "undefined" || pollID.length === 0 ||
            typeof optionValue === "undefined" || optionValue.length === 0) {
            return;
        }

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'wpp_front_new_option',
                'poll_id': pollID,
                'opt_val': optionValue,
            },
            success: function (response) {

                if (response.success) {

                    popupBoxContainer.parent().find('.wpp-options').append(response.data);
                    popupBoxContainer.fadeOut().find('input[type="text"]').val('');
                }
            }
        });

    });

    $(document).on('keypress', '.wpp-new-option input[type="text"]', function (e) {
        if (e.which === 13) {
            $(this).parent().find('.button').trigger('click');
        }
    });


    $(document).on('click', '.wpp-button-new-option', function () {
        $(this).parent().parent().find('.wpp-popup-container').fadeIn().find('input[type="text"]').focus();
    });

    $(document).on('click', '.wpp-popup-container .box-close', function () {
        $(this).parent().parent().fadeOut();
    });


    $(document).on('click', '.wpp-options .wpp-option-single', function (e) {

        let outsideInputArea = $(this).find('.wpp-option-input');

        if (!outsideInputArea.is(e.target) && outsideInputArea.has(e.target).length === 0) {
            $(this).find('label').trigger('click');
        }
    });


    // =================================================================================================================== //

    /*
    $(document).on('click', '.single-poll .woc_alert_box .alert_box_close .fa', function () {

        $(this).parent().parent().parent().fadeOut();
    });



    $(document).on('click', '.wpp_comments_box .wpp_submit_comment', function () {

        poll_id = $(this).attr('poll_id');
        wpp_name = $('.wpp_comments_box .wpp_name').val();
        wpp_email = $('.wpp_comments_box .wpp_email').val();
        wpp_comment = $('.wpp_comments_box .wpp_comment').val();

        if (poll_id == 0 || wpp_name.length == 0 || wpp_email.length == 0 || wpp_comment.length == 0) return;

        $.ajax(
            {
                type: 'POST',
                context: this,
                url: wpp_ajax.wpp_ajaxurl,
                data: {
                    "action": "wpp_ajax_submit_comment",
                    "poll_id": poll_id,
                    "wpp_name": wpp_name,
                    "wpp_email": wpp_email,
                    "wpp_comment": wpp_comment,
                },
                success: function (data) {


                    $('.wpp_comments_box .wpp_comment_header').fadeOut();
                    $('.wpp_comments_box .wpp_comment_section').fadeOut();
                    $(this).fadeOut();

                    $('.wpp_comments_box .wpp_comment_message').hide().html(data).fadeIn();
                }
            });

    });

    $(document).on('click', '.single-poll .wpp_visitor_option_new_confirm', function () {

        __POLL_ID__ = $(this).attr('poll_id');
        option_val = $('.single-poll .wpp_new_option_input').val();

        if (option_val.length == 0) {

            $('.single-poll .wpp_new_option_input').addClass('wpp_input_error');
            return;
        }

        __HTML__ = $(this).html();
        $(this).html('<i class="fa fa-cog fa-spin"></i>');

        $.ajax(
            {
                type: 'POST',
                context: this,
                url: wpp_ajax.wpp_ajaxurl,
                data: {
                    "action": "wpp_ajax_add_new_option",
                    "poll_id": __POLL_ID__,
                    "option_val": option_val,
                },
                success: function (data) {

                    if (data == 'ok') {

                        location.reload();
                    }

                }
            });
    })

    $(document).on('click', '.single-poll .wpp_visitor_option_new', function () {

        __POLL_ID__ = $(this).attr('poll_id');

        $('.single-poll-' + __POLL_ID__ + ' .wpp_new_option_box_container').fadeIn();
    })


    $(document).on('click', '.single-poll .wpp_result', function () {

        __POLL_ID__ = $(this).attr('poll_id');

        $('.single-poll-' + __POLL_ID__ + ' .wpp_result_list').fadeIn();
    })


    $(document).on('click', '.single-poll .wpp_submit', function () {

        __POLL_ID__ = $(this).attr('poll_id');
        __WPP_STATUS__ = $(this).attr('wpp_status');
        __HTML__ = $(this).html();
        __CHECKED__ = [];

        if (__WPP_STATUS__ == 'disabled') return;

        $('.submit_poll_option').each(function () {
            if ($(this).is(':checked')) {
                __CHECKED__.push(this.value);
            }
        });

        if (__CHECKED__.length < 1) return;

        $(this).html('<i class="fa fa-cog fa-spin"></i>');

        $.ajax(
            {
                type: 'POST',
                context: this,
                url: wpp_ajax.wpp_ajaxurl,
                data: {
                    "action": "wpp_ajax_submit_poll",
                    "poll_id": __POLL_ID__,
                    "checked": __CHECKED__,
                },
                success: function (data) {
                    response = JSON.parse(data)
                    _status = response['status'];
                    _notice = response['notice'];


                    if (_status == 0) {
                        $('.single-poll-' + __POLL_ID__ + ' .wpp_notice').html(_notice).addClass('wpp_error').fadeIn();
                    }

                    if (_status == 1) {
                        $('.single-poll-' + __POLL_ID__ + ' .wpp_notice').html(_notice).addClass('wpp_success').fadeIn();
                    }

                    $(this).html(__HTML__);
                }
            });

    })


    */

})(jQuery, window, document, wpp_object);







