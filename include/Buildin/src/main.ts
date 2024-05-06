import "../scss/app.scss";
import * as http from "@il4mb/merapipanel/http";
import { toast } from "@il4mb/merapipanel/toast";
import { Modal } from "@il4mb/merapipanel/modal";
import * as dialog from "@il4mb/merapipanel/dialog";

export interface __MP {
    http: typeof http;
    toast: typeof toast;
    modal: typeof Modal;
    dialog: typeof dialog;
    [key: string]: any;
}

(window as any).__ = {
    "http": http,
    "toast": toast,
    "modal": Modal,
    "dialog": dialog
} as __MP;


const Box = {
    "form.needs-validation": (el) => {

        function validateInput(input) {
            const $input = $(input) as any;
            const pattern = $input.attr("pattern");

            if (pattern) {
                const regex = new RegExp(pattern);

                if (!$input[0].checkValidity()) {
                    // is valid
                    $input.addClass("is-invalid");
                    $input.removeClass("is-valid");
                } else {
                    if (!regex.test($input.val())) {
                        // is invalid
                        $input.addClass("is-invalid");
                        $input.removeClass("is-valid");
                    } else {
                        // is valid
                        $input.addClass("is-valid");
                        $input.removeClass("is-invalid");
                    }
                }

            } else if ($input.attr("minlength") || $input.attr("maxlength")) {
                if ($input.val().length < $input.attr("minlength") || $input.val().length > $input.attr("maxlength")) {
                    // is invalid
                    $input.addClass("is-invalid");
                    $input.removeClass("is-valid");
                } else {
                    // is valid
                    $input.addClass("is-valid");
                    $input.removeClass("is-invalid");
                }

            } else if ($input.attr("min") || $input.attr("max")) {
                if ($input.val() < $input.attr("min") || $input.val() > $input.attr("max")) {
                    // is invalid
                    $input.addClass("is-invalid");
                    $input.removeClass("is-valid");
                } else {
                    // is valid
                    $input.addClass("is-valid");
                    $input.removeClass("is-invalid");
                }
            } else if ($input.attr("required")) {

                if ($input.val() === "") {
                    // is invalid
                    $input.addClass("is-invalid");
                    $input.removeClass("is-valid");
                } else {
                    // is valid
                    $input.addClass("is-valid");
                    $input.removeClass("is-invalid");
                }
            } else {

                $input.addClass("is-valid");
                $input.removeClass("is-invalid");
            }

            return $input.hasClass("is-valid");
        }

        function validateForm(form) {

            const $form = $(form);
            const data: any = {
                el: null,
                valid: true
            }

            for (let i = 0; i < $form.find("input, textarea, select").length; i++) {
                const form_el = $($form.find("input, textarea, select")[i]);
                if (form_el.prop("disabled") || form_el.prop("readonly")) {
                    continue;
                }
                data.el = form_el[0];
                if (!validateInput(form_el[0])) {
                    data.valid = false;
                    break;
                }
            }

            return data;
        }

        el.find("input, textarea, select").on("input", function () {

            if ($(this).prop("disabled") || $(this).prop("readonly")) {
                return;
            }
            validateInput(this);
        });

        el.each(function () {

            this.checkValidity = function () {
                const data = validateForm(this);
                if (!data.valid && data.el) {
                    $(data.el).trigger("focus");
                }
                return data.valid;
            }

            $(this).on("submit", function (e) {
                e.preventDefault();
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            })
        });
    },


    "[onload]": (el: JQuery) => {
        el.each(function () {

            const onload: string = $(this).attr("onload") ?? "";
            function evalInContext() {
                eval(onload);
            }
            evalInContext.call(this);

        })
    },


    "form[xhr-action]": (el: JQuery) => {

        el.each(function () {

            const $this = $(this);
            const action = $this.attr("xhr-action");
            const method = ($this.attr("method") as string || "").toLowerCase();

            $this.on("submit", function (e) {

                e.preventDefault();
                const event = $.Event("xhr-submit");
                $this.trigger(event);
                if (event.isDefaultPrevented()) {
                    return;
                }

                if (http[method] === undefined) {
                    toast("Method not found", 5, "text-danger");
                    return;
                }
                http[method](action, new FormData(this as any))
                    .then((response: any, text: any, xhr: any) => {
                        $(this).trigger("xhr-success", [response, text, xhr]);
                    }).catch((e) => {
                        console.log(e);
                        $(this).trigger("xhr-error", [e]);
                    })
            });
        })
    },
    "img[src]": (el: JQuery) => {

        const placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-5 -5 25 25'%3E%3Cpath d='M7 2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0m4.225 4.053a.5.5 0 0 0-.577.093l-3.71 4.71-2.66-2.772a.5.5 0 0 0-.63.062L.002 13v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4.5z'/%3E%3C/svg%3E";

        el.each(function () {

            const $this = $(this);
            const src = $this.attr("src") ?? "";

            if ($this[0].naturalWidth == 0 && $this[0].naturalHeight == 0) {

                $this.prop("src", placeholder);
                const image = new Image();

                image.onload = () => {
                    $this.prop("src", src);
                }
                image.src = src;

            }
        });
    }
}

$(() => liveReload());

function liveReload() {

    const keys = Object.keys(Box);
    for (let i in keys) {
        $(keys[i]).each(function () {
            let el = $(this);

            let inits = el.data("init") ?? {};

            if (inits[keys[i]]) {
                return;
            }
            inits[keys[i]] = true;
            el.data("init", inits);

            Box[keys[i]](el);

        });
    }

    setTimeout(() => window.requestAnimationFrame(liveReload), 100);
}