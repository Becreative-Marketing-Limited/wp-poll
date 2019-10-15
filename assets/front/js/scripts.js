/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(document).on('click', '.wpp-get-poll-results', function () {

        let pollID = $(this).data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID);

        singlePoll.find('.wpp-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'wpp_get_poll_results',
                'poll_id': pollID,
            },
            success: function (response) {

                if (!response.success) {
                    singlePoll.find('.wpp-responses').addClass('wpp-error').html(response.data).slideDown();
                    return;
                }

                singlePoll.find('.wpp-options .wpp-option-single').each(function () {

                    let optionID = $(this).data('option-id'),
                        percentageValue = response.data.percentages[optionID],
                        singleVoteCount = response.data.singles[optionID],
                        classTobeAdded = '';

                    if (typeof percentageValue === 'undefined') {
                        percentageValue = 0;
                    }

                    if (typeof singleVoteCount === 'undefined' || singleVoteCount.length === 0) {
                        singleVoteCount = 0;
                    }

                    if (percentageValue <= 25) {
                        classTobeAdded = 'results-danger';
                    } else if (percentageValue > 25 && percentageValue <= 50) {
                        classTobeAdded = 'results-warning';
                    } else if (percentageValue > 50 && percentageValue <= 75) {
                        classTobeAdded = 'results-info';
                    } else {
                        classTobeAdded = 'results-success';
                    }

                    if ($.inArray(optionID, response.data.percentages)) {
                        $(this).addClass('has-result').find('.wpp-option-result-bar').addClass(classTobeAdded).css('width', percentageValue + '%');
                        $(this).find('.wpp-option-result').html(singleVoteCount + ' Vote(s)');
                    }
                });
            }
        });
    });


    $(document).on('click', '.wpp-submit-poll', function () {

        let pollID = $(this).data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID), checkedData = [];

        singlePoll.find('.wpp-options .wpp-option-single input[name="submit_poll_option"]').each(function () {
            if ($(this).is(':checked')) {
                checkedData.push(this.value);
            }
        });

        singlePoll.find('.wpp-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'wpp_submit_poll',
                'poll_id': pollID,
                'checked_data': checkedData,
            },
            success: function (response) {
                if (!response.success) {
                    singlePoll.find('.wpp-responses').addClass('wpp-error').html(response.data).slideDown();
                } else {
                    /**
                     * Trigger to enhance on Success of Poll Submission
                     *
                     * @trigger wpp_poll_submission_success
                     */
                    $(document.body).trigger('wpp_poll_submission_success', response);

                    singlePoll.find('.wpp-responses').addClass('wpp-success').html(response.data).slideDown();
                }
            }
        });
    });


    $(document).on('click', 'p.wpp-responses', function () {
        $(this).slideUp();
    });


    $(document).on('click', '.wpp-new-option > button', function () {

        let popupBoxContainer = $(this).parent().parent().parent(),
            pollID = $(this).data('pollid'),
            optionField = $(this).parent().find('input[type="text"]'),
            optionValue = optionField.val();

        if (typeof pollID === "undefined" || pollID.length === 0 ||
            typeof optionValue === "undefined" || optionValue.length === 0) {

            $(this).parent().find('span').fadeIn(100);
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


    $(document).on('keyup', '.wpp-new-option input[type="text"]', function (e) {
        if (e.which === 13) {
            $(this).parent().find('.wpp-button').trigger('click');
        }

        if ($(this).val().length > 0) {
            $(this).parent().find('span').hide();
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

})(jQuery, window, document, wpp_object);







