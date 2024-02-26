import './app.css';
import './fontawesome/css/all.min.css';

import "./style/color.scss";
import "./style/input.scss";
import "./style/button.scss";
import "./style/header.scss";
import "./style/nav.scss";


if (!window.merapi) {
    window.merapi = require("./merapi");
}


function validate(el) {

    const $input = $(el);
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


$(document).on('DOMContentLoaded', function () {

    window.$.fn.validate = function () { return validate(this) };

    $('[onload]').each(function () {
        const $this = $(this);
        try {
            eval($this.attr('onload'));
        } catch (e) {
            console.error(e);
        }
    });

    liveReload();
})

const liveCallback = {
    ".dropdown": {
        initial: function (e) {
            let $this = $(e);
            $this.find('[data-act-trigger=dropdown]').on('click', function () {
                $('.dropdown').not($this).removeClass('open');
                $this.toggleClass('open');
            });
            $(document).on('click', function (e) {
                if (!$(e.target).closest($this).length) {
                    $this.removeClass('open');
                }
            });
        }
    },
    "input[type='file'].form-input": {
        initial: function (e) {
            e.on("change", function () {
                $(this).attr("data-file-name", this.files[0].name);
            })
        }
    },
    "input": {
        initial(el) {
            if (el.prop('required') || el.attr('pattern') || el.attr('min') || el.attr('max')) {
                if ($(this).is(":hidden")) return;

                $(el).on("input", function () {
                    if ($(this).is(":focus")) {
                        validate(this);
                        $(this).trigger("focus");
                    }
                });

                if (el.closest('form').length > 0) {
                    let form = $(el.closest('form'));
                    form.on('submit', function (evt) {
                        if (!validate(el)) {
                            evt.preventDefault();
                            evt.stopPropagation();
                        }
                    })
                }
            }
        }
    },
    "form[method='xhr::post']": {
        initial: function (el) {
            el.on("submit", function (evt) {
                evt.preventDefault();
                let formData = new FormData(this);
                merapi.http.post(this.action, formData).then((result, status, xhr) => {
                    if (xhr.status == 200) {
                        el.trigger("xhr:success", { result: result, status: status, xhr: xhr, event: evt });
                    } else {
                        el.trigger("xhr:error", { error: result, status: status, xhr: xhr, event: evt });
                    }
                }).catch((err) => {
                    el.trigger("xhr:error", { error: err, event: evt });
                })
            })
        }
    },
    "form[method='xhr::get']": {
        initial: function (el) {
            el.on("submit", function (evt) {
                evt.preventDefault();
                let formData = new FormData(this);
                merapi.http.get(this.action, formData).then((result, status, xhr) => {
                    if (xhr.status == 200) {
                        el.trigger("xhr:success", { result: result, status: status, xhr: xhr, event: evt });
                    } else {
                        el.trigger("xhr:error", { error: result, status: status, xhr: xhr, event: evt });
                    }
                }).catch((err) => {
                    el.trigger("xhr:error", { error: err, event: evt });
                })
            })
        }
    }
}



function liveReload() {

    const ElementEvents = [
        "click", // User clicks an element
        "dblclick", // User double-clicks an element
        "mouseenter", // Mouse pointer enters the element
        "mouseleave", // Mouse pointer leaves the element
        "mouseover", // Mouse pointer is over the element
        "mouseout", // Mouse pointer leaves the element or one of its children
        "mousedown", // User presses a mouse button over an element
        "mouseup", // User releases a mouse button over an element
        "focus", // Element gains focus
        "blur", // Element loses focus
        "change", // The value of an element changes
        "submit", // A form is submitted
        "keydown", // User is pressing a key
        "keyup", // User releases a key
        "keypress", // Character is being inserted
        "resize", // Element is resized
        "scroll", // Element is scrolled
    ];

    Object.keys(liveCallback).forEach(selector => {

        let fn = liveCallback[selector];
        let target = $(selector);

        if (!target.length) return;

        target.each(function () {
            let $this = $(this);

            Object.keys(fn).forEach(method => {

                let attached = $this.data('listener-attached') ?? [];
                if (attached.includes(method)) return;

                if (ElementEvents.includes(method)) {
                    $this.on(method, fn[method]);
                } else if (method === 'initial') {
                    fn.initial($this);
                } else {
                    console.error(`Live reload cant find Method ${method} not found`);
                }

                attached.push(method);
                $this.data('listener-attached', attached);
            })
        })
    })

    setTimeout(() => {
        window.requestAnimationFrame(liveReload);
    }, 800);
}
