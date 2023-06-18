/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(document).on('ready elementor/popup/show', function () {

        let optionSingle = $('.liquidpoll-option-single');

        optionSingle.on('click', function () {
            optionSingle.removeClass('active');
            $(this).addClass('active');
        });

        $('.theme-6, .theme-7').on('click', function () {
            $(this).find('.liquidpoll-option-single input[name="submit_poll_option"]').not(':checked').prop("checked", true);
        });
    });


    $(document).on('click', '.liquidpoll-get-poll-results', function () {

        let resultButton = $(this),
            pollID = resultButton.data('poll-id'),
            marginLeft = 60;

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
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').find('span.message').html(response.data).parent().slideDown();
                    return;
                }

                singlePoll.addClass('rendered-results').find('.liquidpoll-options .liquidpoll-option-single').each(function () {

                    let optionID = $(this).data('option-id'),
                        percentageValue = response.data.percentages[optionID],
                        singleVoteCount = response.data.singles[optionID], optionResultsText = '';

                    if (typeof percentageValue === 'undefined') {
                        percentageValue = 0;
                    }

                    if (typeof singleVoteCount === 'undefined' || singleVoteCount.length === 0) {
                        singleVoteCount = 0;
                    }

                    optionResultsText = singleVoteCount + ' ' + pluginObject.voteText;
                    if (singlePoll.hasClass('results-type-percentage')) {
                        optionResultsText = percentageValue + '%';
                    }

                    if ($.inArray(optionID, response.data.percentages)) {

                        let pollSingle = $(this).parent().parent().parent();

                        if (
                            pollSingle.hasClass('theme-11') ||
                            pollSingle.hasClass('theme-12')
                        ) {
                            $(this).addClass('has-result').find('.liquidpoll-option-result-bar-inner').css('width', percentageValue + '%');
                        } else {
                            $(this).addClass('has-result').find('.liquidpoll-option-result-bar').css('width', percentageValue + '%');
                        }

                        // $(this).append('<span class="percentage-bar" style="width: ' + percentageValue + '% ;"></span>');
                        $(this).find('.percentage-bar').css('width', percentageValue + '%');

                        if (
                            pollSingle.hasClass('theme-4') ||
                            pollSingle.hasClass('theme-6') ||
                            pollSingle.hasClass('theme-7')
                        ) {
                            let progressBar = $(this).addClass('has-result').find('.liquidpoll-votes-count'),
                                radius = progressBar.find('circle.complete').attr('r'),
                                circumference = 2 * Math.PI * radius,
                                strokeDashOffset = circumference - ((percentageValue * circumference) / 100);

                            progressBar.find('.percentage').html(percentageValue + '%');
                            progressBar.find('circle.complete').removeAttr('style');
                            progressBar.find('circle.complete').animate({'stroke-dashoffset': strokeDashOffset}, 1250);
                        } else {
                            $(this).addClass('has-result').find('.liquidpoll-votes-count').html(optionResultsText);
                        }

                        if (percentageValue === 0) {
                            marginLeft = 0;
                        }

                        if (
                            pollSingle.hasClass('theme-11') ||
                            pollSingle.hasClass('theme-12')
                        ) {
                            $(this).find('.liquidpoll-option-result').html(optionResultsText);
                            $(this).find('.liquidpoll-votes-count').html(singleVoteCount + ' ' + pluginObject.voteText);
                        } else {
                            $(this).find('.liquidpoll-option-result').html(optionResultsText).css('left', 'calc(' + percentageValue + '% - ' + marginLeft + 'px)');
                        }
                    }
                });

                if (singlePoll.hasClass('theme-12') && singlePoll.hasClass('auto-populate')) {
                    return false;
                } else {
                    resultButton.parent().fadeOut('slow', 'linear');
                }
            }
        });
    });


    $(document).on('submit', '.liquidpoll-form', function () {

        let optinForm = $(this),
            optinFormData = optinForm.serialize(),
            singlePoll = optinForm.parent();

        if (singlePoll.hasClass('poll-type-poll') || singlePoll.hasClass('poll-type-nps') || singlePoll.hasClass('poll-type-reaction')) {
            $.ajax({
                type: 'POST',
                context: this,
                url: pluginObject.ajaxurl,
                data: {
                    'action': 'liquidpoll_submit_optin_form',
                    'form_data': optinFormData,
                },
                success: function (response) {
                    if (response.success) {
                        $('.liquidpoll-get-poll-results').trigger('click');

                        singlePoll.find('.liquidpoll-form').fadeOut(100);
                        setTimeout(function () {
                            singlePoll.find('.poll-content').fadeIn(100);
                            singlePoll.find('.nps-form').fadeIn(100);
                            singlePoll.find('.reaction-container').fadeIn(100);
                        }, 150);
                    }
                }
            });
        }

        return false;
    });


    $(document).on('click', '.liquidpoll-submit-poll', function () {

        let pollID = $(this).data('poll-id');

        if (typeof pollID === 'undefined') {
            return;
        }

        let singlePoll = $('#poll-' + pollID), checkedData = [];

        singlePoll.find('.liquidpoll-options .liquidpoll-option-single').each(function () {
            if ($(this).hasClass('active')) {
                checkedData.push($(this).find('input[name="submit_poll_option"]').attr('value'));
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
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-error').find('span.message').html(response.data).parent().slideDown();
                } else {
                    /**
                     * Trigger to enhance on Success of Poll Submission
                     *
                     * @trigger liquidpoll_poll_submission_success
                     */
                    $(document.body).trigger('liquidpoll_poll_submission_success', response);

                    singlePoll.addClass('vote-done');
                    singlePoll.find('.liquidpoll-responses').addClass('liquidpoll-success').find('span.message').html(response.data).parent().slideDown();
                    singlePoll.find('.voted-users-only').removeClass('voted-users-only');

                    if (singlePoll.hasClass('has-form')) {
                        singlePoll.find('.poll-content').fadeOut(100);
                        setTimeout(function () {
                            singlePoll.find('.liquidpoll-form').fadeIn(100);
                        }, 150);
                    }
                }
            }
        });
    });


    $(document).on('click', 'p.liquidpoll-responses .close', function () {
        $(this).parent().slideUp();
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


    $(document).on('change', '.nps-single input[name="nps_score"]', function () {

        let npsSelectionField = $(this),
            npsSelectionFieldVal = npsSelectionField.val(),
            npsSelectionLI = npsSelectionField.parent(),
            npsSelectionUL = npsSelectionLI.parent(),
            npsSingle = npsSelectionUL.parent().parent(),
            npsCommentBox = npsSingle.find('.liquidpoll-comment-box'),
            npsSubmitButton = npsSingle.find('.nps-button-wrap > .liquidpoll-submit-poll');

        npsSelectionUL.find('> li').removeClass('active');
        npsSelectionLI.toggleClass('active');

        if (npsSelectionFieldVal.length > 0) {
            npsCommentBox.fadeIn('300');
            npsSubmitButton.fadeIn('300');
        }
    });


    $(document).on('ready', function () {

        let $r = $('input[type="range"]'),
            $ruler = $('<div class="rangeslider__ruler" />');

        $r.rangeslider({
            polyfill: false,
            onInit: function () {
                $ruler[0].innerHTML = getRulerRange(this.min, this.max, this.step);
                this.$range.prepend($ruler);
            },
            onSlide: function (position, value) {

                let npsSingle = $r.parent().parent(),
                    npsCommentBox = npsSingle.find('.liquidpoll-comment-box'),
                    npsSubmitButton = npsSingle.find('.nps-button-wrap > .liquidpoll-submit-poll');

                if (value > 0) {
                    npsCommentBox.fadeIn('300');
                    npsSubmitButton.fadeIn('300');
                }
            }
        });

        function getRulerRange(min, max, step) {
            let range = '', i = 0;
            while (i <= max) {
                range += i + ' ';
                i = i + step;
            }
            return range;
        }

        $.fn.roundSlider.prototype._invertRange = true;
        let roundHandle = $('#nps_score');

        // this is core functionality to generate the numbers
        $.fn.roundSlider.prototype.defaults.create = function () {
            let o = this.options, tickInterval = 1;

            for (let i = o.min; i <= o.max; i += tickInterval) {
                let angle = this._valueToAngle(i),
                    numberTag = this._addSeperator(angle, "rs-custom"),
                    number = numberTag.children();

                number.clone().css({
                    "width": o.width + this._border(),
                    "margin-top": this._border(true) / -2
                });
                number.removeClass().addClass("rs-number").html(i).rsRotate(-angle);
            }
        }
        roundHandle.roundSlider({
            sliderType: "min-range",
            editableTooltip: false,
            showTooltip: false,
            radius: 300,
            width: 30,
            value: 0,
            handleShape: "square",
            handleSize: 20,
            circleShape: "half-top",
            startAngle: 0,
            min: 0,
            max: 10,
            step: 1,
            change: "onValueChange",
        });
        roundHandle.on('change', function (e) {
            let colors = ['#6265ea', '#6866e8', '#6766e9', '#6d68e8', '#7369e6', '#7a6be4', '#7f6be2', '#866de1', '#876ddf', '#8a6ddf', '#8b6edf'];
            document.documentElement.style.setProperty('--bgcolor', colors[e.value]);

            if (e.value > 0 && e.value < 10) {

                let npsSingle = roundHandle.parent(),
                    npsCommentBox = npsSingle.find('.liquidpoll-comment-box'),
                    npsSubmitButton = npsSingle.find('.nps-button-wrap > .liquidpoll-submit-poll');

                roundHandle.attr('val', 'yes-' + e.value);
                npsCommentBox.fadeIn('300');
                npsSubmitButton.fadeIn('300');
            } else {
                roundHandle.attr('val', '0');
            }
        });


        // let pollSingle = $('.poll-single');
        //
        // if (pollSingle.length > 0 && pollSingle.hasClass('theme-12')) {
        //     pollSingle.addClass('auto-populate').find('.liquidpoll-get-poll-results').trigger('click');
        // }
    });


    $(document).on('click', '.nps-single.theme-4 #handle1 span.rs-number', function () {
        $(this).parent().trigger("click");
    });


    $(document).on('keyup', '.nps-single .liquidpoll-comment-box textarea', function () {
        let npsCommentBox = $(this),
            npsCommentBoxWrap = npsCommentBox.parent(),
            npsCommentValue = npsCommentBox.val(),
            npsButtonSubmit = npsCommentBoxWrap.parent().find('.liquidpoll-submit-poll');

        if (npsCommentBoxWrap.hasClass('obvious')) {
            if (npsCommentValue.length > 0) {
                npsButtonSubmit.removeClass('disabled');
            } else {
                npsButtonSubmit.addClass('disabled');
            }
        }
    });


    $(document).on('submit', '.nps-single form.nps-form', function () {

        let npsForm = $(this),
            poll_id = npsForm.data('poll-id'),
            single_poll = $('#nps-' + poll_id),
            npsFormData = npsForm.serialize(),
            submissionButton = npsForm.find('.liquidpoll-submit-poll');

        npsForm.find('.liquidpoll-responses').slideUp();

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxurl,
            data: {
                'action': 'liquidpoll_submit_nps',
                'poll_id': poll_id,
                'form_data': npsFormData,
            },
            success: function (response) {
                if (!response.success) {
                    npsForm.find('.liquidpoll-responses').addClass('liquidpoll-error').find('span.message').html(response.data).parent().slideDown();
                } else {
                    $(document.body).trigger('liquidpoll_poll_submission_success', response);

                    npsForm.find('.liquidpoll-responses').addClass('liquidpoll-success').find('span.message').html(response.data).parent().slideDown();

                    submissionButton.fadeOut();
                    npsForm.addClass('submission-done');


                    if (single_poll.hasClass('has-form')) {
                        single_poll.find('.nps-form').fadeOut(100);
                        setTimeout(function () {
                            single_poll.find('.liquidpoll-form').fadeIn(100);
                        }, 150);
                    }
                }
            }
        });

        return false;
    });


})(jQuery, window, document, liquidpoll_object);







