import './app.css';
import './fontawesome/css/all.min.css';

import "./style/color.scss";
import "./style/input.scss";
import "./style/nav.scss";

import $, { fn } from 'jquery';

if (!window.merapi) {
    window.merapi = require("./merapi");
    // console.log(window)
}


fn.invalidate = function () {
    let $this = $(this);
    $this.addClass("invalid");



    $this.trigger("focus");
}

fn.validate = function () {

    let $this = $(this);
    let pattern = $this.attr('pattern');
    let val = $this.val();
    let parent = $this.parent();

    if ((pattern && !new RegExp(pattern).test(val)) || ($this.prop("required") && val.length === 0)) {

        $this.addClass("invalid").attr('aria-invalid', 'true');
        let message = $this.attr('invalid-message');
        if (message && !parent.hasClass('invalid-feedback')) {
            let wrapper = $(`<div class="invalid-feedback"><small class='block w-full text-red-400'>${$this.attr('invalid-message')}</small></div>`);
            wrapper.insertAfter($this);
            $this.remove();
            wrapper.prepend($this);
        }
        $this.trigger("focus");
        return false;

    } else if ((pattern && new RegExp(pattern).test(val)) || ($this.prop("required") && val.length > 0)) {

        $this.removeClass("invalid").removeAttr('aria-invalid');
        if (parent.hasClass('invalid-feedback')) {
            let input = parent.find("input");
            input.insertBefore(parent);
            parent.remove();
        }
        return true;

    }

    return false;
};


$(document).on('DOMContentLoaded', function () {

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
