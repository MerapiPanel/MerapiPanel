import './app.css';
import './fontawesome/css/all.min.css';

import "./style/color.scss";
import "./style/input.scss";
import "./style/button.scss";
import "./style/nav.scss";

// import $ from 'jquery';

if (!window.merapi) {
    window.merapi = require("./merapi");
    // console.log(window)
}




$(document).on('DOMContentLoaded', function () {

    window.$.fn.validate = function () {
        const $input = $(this);
        const pattern = $input.attr('pattern');
        const min = parseFloat($input.attr('min'));
        const max = parseFloat($input.attr('max'));

        // Precompile the RegExp for efficiency if pattern is provided
        const regex = pattern ? new RegExp(pattern) : null;
        const required = $input.prop("required");

        function removeInvalidCondition() {
            const $parent = $input.parent();
            $input.removeClass("invalid").removeAttr('aria-invalid');
            if ($parent.hasClass('invalid-feedback')) {
                const $inputInParent = $parent.find("input");
                $inputInParent.insertBefore($parent);
                $parent.remove();
            }
        }

        function showInvalidCondition() {
            $input.addClass("invalid").attr('aria-invalid', 'true');
            const message = $input.attr('invalid-message');
            if (message && !$input.parent().hasClass('invalid-feedback')) {
                const wrapper = $(`<div class="invalid-feedback"><small style='display: none;' class='w-full text-red-400'>${$input.attr('invalid-message')}</small></div>`);
                wrapper.insertAfter($input);
                $input.remove();
                wrapper.prepend($input);
                wrapper.find('small').fadeIn(200);
            }
            $input.trigger("focus");
        }


        function isValid() {
            const value = $input.val();
            if (!value) return required ? false : true; // Check for required field
            if (min !== undefined && value.length < min) return false; // Check for min value
            if (max !== undefined && value.length > max) return false; // Check for max value
            return (regex ? regex.test(value) : true); // Check against pattern
        }

        function validateInput() {
            if (!isValid()) {
                showInvalidCondition();
            } else {
                removeInvalidCondition();
            }
        }

        // Initial validation check
        validateInput();

        return isValid();
    };


    $('[onload]').each(function () {
        const $this = $(this);
        try {
            eval($this.attr('onload'));
        } catch (e) {
            console.error(e);
        }
    });

    $('[data-act-target]').each(function () {

        let target = $this.attr('data-act-target');
    });

    liveReload();

    // console.clear()
})



function liveReload() {
    $('.dropdown').each(function () {
        const $this = $(this);

        // Check if the element already has the event listener attached
        if ($this.data('listener-attached')) {
            return; // Exit the loop for this element
        }

        // Attach the event listener
        $this.find('[data-act-trigger=dropdown]').on('click', function () {
            $('.dropdown').not($this).removeClass('open');
            $this.toggleClass('open');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest($this).length) {
                $this.removeClass('open');
            }
        });

        // Mark the element as having the event listener attached
        $this.data('listener-attached', true);
    });

    setTimeout(() => {
        window.requestAnimationFrame(liveReload);
    }, 800);
}
