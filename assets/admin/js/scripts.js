/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {
        $(".poll-options").sortable({handle: ".option-move", revert: true});
        $(".poll_option_container").sortable({handle: ".poll_option_single_sorter"});
    });


    /**
     * Add new option in poll meta box
     */
    $(document).on('click', '.wpp-add-poll-option', function () {

        $.ajax({
            type: 'GET',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                "action": "wpp_ajax_add_option",
            },
            success: function (response) {

                if (response.success) {
                    $(response.data).hide().appendTo('.poll-options').slideDown();
                    // $('.poll-options').append( response.data );
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






    $(document).on('click', '.wpp_td_add_section', function () {

        var section_key = $(this).attr('section_key');
        var label = $(this).parent().find('.wpp_td_label').html();

        var __DATA__ = "<li class='wpp_td_single " + section_key + "'><span class='wpp_td_label'>" + label + "</span>" +
            "<div class='wpp_td_icon wpp_td_single_remove'><i class='fa fa-times'></i></div>" +
            "<div class='wpp_td_icon wpp_td_single_sorter'><i class='fa fa-sort'></i></div>" +
            "<input type='hidden' name='wpp_poll_template[]' value='" + section_key + "' /></li>";

        $('.wpp_td_templates').append(__DATA__).find("." + section_key).hide().fadeIn();
    });


    $(document).on('click', '.wpp_td_templates .wpp_td_single .wpp_td_single_remove', function () {

        var step = $(this).attr('step');

        if (step === 'f') {
            $(this).html("<i class='fa fa-check'></i>");
            $(this).attr('step', 'l');
        } else $(this).parent().remove();
    });


    $(document).on('click', '.wp_poll_shortcode_copy', function () {

        var __COPY_TEXT__ = $('#wp_poll_shortcode').val();

        try {
            $('#wp_poll_shortcode').select();
            document.execCommand('copy');
        } catch (e) {
            alert(e);
        }
    })


    $(document).on('change', '#wpp_report_form select', function () {

        $(this).closest('form').trigger('submit');
    })

    function copyToClipboard(element) {
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val(jQuery(element).val()).select();
        document.execCommand("copy");
        $temp.remove();

        jQuery('#wp_shortcode_notice').remove();
        jQuery(element).parent().append('<span id="wp_shortcode_notice">Copied to Clipboard.</span>');
    }


})(jQuery, window, document, wpp_object);







