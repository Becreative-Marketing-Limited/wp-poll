/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(document).on('click', '.liquidpoll-get-poll-results', function () {

        let resultButton = $(this),
            pollID = resultButton.data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID);

        singlePoll.find('.liquidpoll-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_get_poll_results',
                'poll_id': pollID,
            },
            success: function (response) {

                if (!response.success) {
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').html(response.data).slideDown();
                    return;
                }

                singlePoll.find('.liquidpoll-options .liquidpoll-option-single').each(function () {

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

                    if ($.inArray(optionID, response.data.percentages)) {
                        $(this).addClass('has-result').find('.liquidpoll-option-result-bar').css('width', percentageValue + '%');
                        $(this).find('.liquidpoll-option-result').html(singleVoteCount + ' ' +  pluginObject.voteText);
                    }
                });

                resultButton.parent().fadeOut('slow', 'linear');
            }
        });
    });


    $(document).on('click', '.liquidpoll-submit-poll', function () {

        let pollID = $(this).data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID), checkedData = [];

        singlePoll.find('.liquidpoll-options .liquidpoll-option-single input[name="submit_poll_option"]').each(function () {
            if ($(this).is(':checked')) {
                checkedData.push(this.value);
            }
        });

        singlePoll.find('.liquidpoll-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_submit_poll',
                'poll_id': pollID,
                'checked_data': checkedData,
            },
            success: function (response) {
                if (!response.success) {
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').html(response.data).slideDown();
                } else {
                    /**
                     * Trigger to enhance on Success of Poll Submission
                     *
                     * @trigger liquidpoll_poll_submission_success
                     */
                    $(document.body).trigger('liquidpoll_poll_submission_success', response);

                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-success').html(response.data).slideDown();
                }
            }
        });
    });


    $(document).on('click', 'p.liquidpoll-responses', function () {
        $(this).slideUp();
    });


    $(document).on('click', '.liquidpoll-new-option > button', function () {

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
                'action': 'liquidpoll_front_new_option',
                'poll_id': pollID,
                'opt_val': optionValue,
            },
            success: function (response) {

                if (response.success) {

                    popupBoxContainer.parent().find('.liquidpoll-options').append(response.data);
                    popupBoxContainer.fadeOut().find('input[type="text"]').val('');
                }
            }
        });

    });


    $(document).on('keyup', '.liquidpoll-new-option input[type="text"]', function (e) {
        if (e.which === 13) {
            $(this).parent().find('.liquidpoll-button').trigger('click');
        }

        if ($(this).val().length > 0) {
            $(this).parent().find('span').hide();
        }
    });


    $(document).on('click', '.liquidpoll-button-new-option', function () {
        $(this).parent().parent().find('.liquidpoll-popup-container').fadeIn().find('input[type="text"]').focus();
    });


    $(document).on('click', '.liquidpoll-popup-container .box-close', function () {
        $(this).parent().parent().fadeOut();
    });


    $(document).on('click', '.liquidpoll-options .liquidpoll-option-single', function (e) {

        let outsideInputArea = $(this).find('.liquidpoll-option-input');

        if (!outsideInputArea.is(e.target) && outsideInputArea.has(e.target).length === 0) {
            $(this).find('label').trigger('click');
        }
    });

})(jQuery, window, document, liquidpoll_object);







