/**
 * Front Script
 */

(function ($) {

    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/liquidpoll-widget-nps.default', function (scope, $) {

            let $r = $(scope).find('input[type="range"]'), $ruler = $('<div class="rangeslider__ruler" />');

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
            let roundHandle = $(scope).find('#nps_score');

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
        });
    });

})(jQuery);