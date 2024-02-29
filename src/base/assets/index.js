import './app.css';
import './fontawesome/css/all.min.css';
import "./style/app.scss";


if (!window.merapi) {
    window.merapi = require("./merapi");
}

console.log(merapi);


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
            $parent.find('small').fadeOut({
                duration: 200,
                complete: function () {
                    const $inputInParent = $parent.find("input, textarea");
                    $inputInParent.insertBefore($parent);
                    $parent.remove();
                    $input.trigger("focus");
                }
            });
        }
    }

    function showInvalidCondition() {
        $input.addClass("invalid").attr('aria-invalid', 'true');
        const message = $input.attr('invalid-message');
        if (message && !$input.parent().hasClass('invalid-feedback')) {
            const wrapper = $(`<div class="invalid-feedback"><small style='display: none;' class='w-full text-red-400'>${$input.attr('invalid-message')}</small></div>`);
            wrapper.insertAfter($input);
            $input.detach();
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

$(function () {

    $.fn.validate = function () { return validate(this) };

    $('[onload]').each(function () {
        const $this = $(this);
        try {
            eval($this.attr('onload'));
        } catch (e) {
            console.error(e);
        }
    });

    liveReload();
});

const liveCallback = {
    ".tooltip": {
        initial: function (e) {

            let $this = $(e);
            $this.on("mouseenter touchstart", function () {
                if (e.attr("onShow")) {
                    eval(e.attr("onShow"));
                }
            });
            $this.on("mouseleave touchend", function () {
                if (e.attr("onHide")) {
                    eval(e.attr("onHide"));
                }
            });
        }
    },
    ".dropdown": {
        initial: function (e) {
            let $this = $(e);
            $this.find('.dropdown-toggle').on('click', function () {
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
    "input,textarea": {
        initial(el) {
            if (el.prop('required') || el.attr('pattern') || el.attr('min') || el.attr('max')) {
                if ($(this).is(":hidden")) return;

                $(el).on("input", function () {
                    if ($(this).is(":focus")) {
                        validate(this);
                        $(this).trigger("focus");
                    }
                });
                $(el).on("change", () => setTimeout(() => validate(el), 200))

                if (window.$(el).closest('form').length > 0) {
                    let form = window.$(window.$(el).closest('form'));
                    form.on('submit', function (evt) {
                        evt.preventDefault();

                        let invalid = [];
                        $(form).find('input,textarea').each(function (i, el) {
                            if ($(el).is(":visible") && ($(el).prop('required') || $(el).attr('pattern') || $(el).attr('min') || $(el).attr('max'))) {
                                if (!validate(el)) {
                                    invalid.push(el);
                                }
                            }
                        })
                        $(this).data("invalid", invalid);
                    })
                }
            }
        }
    },
    "form[method='xhr::post']": {
        initial: function (el) {
            $(el).on("submit", function (evt) {

                evt.preventDefault();

                if ($(this).data('invalid') && $(this).data('invalid').length > 0) {
                    $($(this).data('invalid')[0]).trigger("focus");
                    merapi.toast("Please fill all required fields", 5, "text-danger");
                    evt.stopPropagation();
                    return;
                }

                const $this = window.$(this); // use window to get element by jQuery user context
                let formData = new FormData($this[0]);
                merapi.http.post(this.action, formData).then((result, status, xhr) => {

                    if (xhr.status == 200) {
                        $this.trigger("xhr::success", { message: result.message, code: result.code, result, status, xhr });
                    } else {
                        throw new Error(result.message, result.code);
                    }
                }).catch((err) => {
                    $this.trigger("xhr::error", { message: err.message || err.statusText || err.responseText, code: err.code });
                })
            })
        }
    },
    "form[method='xhr::get']": {
        initial: function (el) {
            window.$(el).on("submit", function (evt) {

                evt.preventDefault();

                if ($(this).data('invalid') && $(this).data('invalid').length > 0) {
                    $($(this).data('invalid')[0]).trigger("focus");
                    merapi.toast("Please fill all required fields", 5, "text-danger");
                    evt.stopPropagation();
                    return;
                }

                const $this = window.$(this); // use window to get element by jQuery user context
                let formData = new FormData(this);
                merapi.http.get(this.action, formData).then((result, status, xhr) => {

                    if (xhr.status == 200) {
                        $this.trigger("xhr::success", { message: result.message, code: result.code, result, status, xhr });
                    } else {
                        throw new Error(result.message, result.code);
                    }
                }).catch((err) => {
                    $this.trigger("xhr::error", {
                        message: err.message || err.statusText || err.responseText,
                        code: err.code
                    });
                });
            });
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
        let target = window.$(selector);

        if (!target.length) return;

        target.each(function () {
            let $this = window.$(this);

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

    setTimeout(liveReload, 800);
}
