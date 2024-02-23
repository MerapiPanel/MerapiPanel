//import $ from 'jquery';

const proggressbars = $(`<div class='http-progress'><div class='download running-strip'></div><div class='upload running-strip'></div></div>`);
var isOnAjax = false;
$(document).on("ajaxSend", function (e) {
    $(document.body).append(proggressbars);
}).on("ajaxComplete", function () {
    $('.http-progress').remove();
    isOnAjax = false;
});


function createXmlHttpRequest() {

    // if (isOnAjax) {
    //     Toast.create('Please wait...', 'hsl(51, 50%, 45%)', 10);
    //     return false;
    // }

    isOnAjax = true;

    proggressbars.css({ position: 'fixed', width: '100%', height: '5px', 'background-color': '#00000000', 'box-shadow': '0 1px 2px #00000045', top: 0, 'z-index': 999 })
    proggressbars.find('.download').css({ transision: '1s', position: 'absolute', top: 0, width: '0%', height: '100%', 'background-color': '#0091ff', 'z-index': 2 })
    proggressbars.find('.upload').css({ transision: '1s', position: 'absolute', top: 0, width: '5%', height: '100%', 'background-color': '#eaeaea', 'z-index': 1 })

    const xhr = new XMLHttpRequest();

    xhr.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
            let complete = (evt.loaded / evt.total * 100 | 0);
            $('.http-progress').find('.download').css('width', `${complete}%`);
            isOnAjax = false;
        }
    });

    xhr.upload.addEventListener("progress", (evt) => {
        if (evt.lengthComputable) {
            let complete = Math.ceil((evt.loaded / evt.total) * 100);
            $('.http-progress').find('.upload').css('width', `${complete}%`);
        }
    });
    return xhr;
}

/**
 * Creates a modal with the specified title, content, and action buttons.
 *
 * @param {string} title - The title of the modal.
 * @param {string} content - The content of the modal body.
 * @param {Object} [action] - Optional settings for the action buttons.
 * @param {Object} [action.positive] - The settings for the positive action button.
 * @param {string} [action.positive.text] - The text to display on the positive action button.
 * @param {string} [action.positive.class] - The CSS class to apply to the positive action button.
 * @param {function} [action.positive.callback] - The callback function to execute when the positive action button is clicked.
 * @param {Object} [action.negative] - The settings for the negative action button.
 * @param {string} [action.negative.text] - The text to display on the negative action button.
 * @param {string} [action.negative.class] - The CSS class to apply to the negative action button.
 * @param {function} [action.negative.callback] - The callback function to execute when the negative action button is clicked.
 * @return {Object} The created modal object.
 */
const createModal = (title, content, action = {
    positive: {
        text: 'Ok',
        class: 'btn btn-primary',
        callback: null
    },
    negative: {
        text: 'Close',
        class: 'btn btn-secondary',
        callback: null
    }
}) => {

    const element = $(
        `<div class='modal' style='display: none;'>
            <div class='modal-dialog'>
                <div class='modal-header'>
                    <h5 class='modal-title'>${title}</h5>
                    <button type='button' class='btn-close'><i class='fa-solid fa-xmark'></i></button>
                </div>
                <div class='modal-content'></div>
                <div class='modal-footer'>
                    <button data-modal-act='negative' type='button' class='btn btn-secondary'>Close</button>
                    <button data-modal-act='positive' type='button' class='btn btn-primary'>Save changes</button>
                </div>
            </div>
        </div>`);

    const dialog = element.find('.modal-dialog');
    const header = element.find('.modal-header');
    const footer = element.find('.modal-footer');
    const body = element.find('.modal-content');
    const close = element.find('.btn-close');
    const btnPositive = element.find('[data-modal-act="positive"]');
    const btnNegative = element.find('[data-modal-act="negative"]');

    body.append(content);

    if (!action.positive) {
        btnPositive.remove();
    } else {
        btnPositive.text(action.positive.text);
        btnPositive.addClass(action.positive.class);
        btnPositive.on('click', () => {
            if (action.positive.callback) {
                action.positive.callback();
            } else {
                modal.hide();
            }
        })
    }

    if (!action.negative) {
        btnNegative.remove();
    } else {
        btnNegative.text(action.negative.text);
        btnNegative.addClass(action.negative.class);
        btnNegative.on('click', () => {
            if (action.negative.callback) {
                action.negative.callback();
            } else {
                modal.hide();
            }
        })
    }

    close.on('click', () => {
        modal.hide();
    });

    $(document.body).append(element);

    function show() {
        $(element).trigger("modal:show");
        element.fadeIn();
    }


    function hide() {
        $(element).trigger("modal:hide");
        element.fadeOut({
            complete: () => {
                element.remove();
            }
        });
    }



    /**
     * Sets the action for the given type of button.
     *
     * @param {string} act - The type of button action ('positive' or 'negative')
     * @param {Object} [opt] - Optional settings for the button
     * @param {string} [opt.text] - The text to display on the button
     * @param {string} [opt.class] - The CSS class to apply to the button
     * @param {function} [opt.callback] - The callback function to execute when the button is clicked
     */
    function setAction(act, opt = {
        text: null,
        class: null,
        callback: null
    }) {
        if (act == 'positive' || act == '+') {

            btnPositive.text(opt.text ?? btnPositive.text());
            btnPositive.addClass(opt.class ?? btnPositive.attr('class'));
            btnPositive.off('click').on('click', () => {
                if (opt.callback) {
                    opt.callback();
                } else {
                    modal.hide();
                }
            })
        } else if (act == 'negative' || act == '-') {
            btnNegative.text(opt.text ?? btnNegative.text());
            btnNegative.addClass(opt.class ?? btnNegative.attr('class'));
            btnNegative.off('click').on('click', () => {
                if (opt.callback) {
                    opt.callback();
                } else {
                    modal.hide();
                }
            })
        } else throw new Error('Invalid action');
    }




    /**
     * Sets the title of the modal header.
     *
     * @param {string} title - The title to be set.
     * @return {void} This function does not return a value.
     */
    function setTitle(title) {
        if (header.find('h5')) {
            header.find('h5').text(title);
        } else {
            header.prepend(`<h5 class='modal-title'>${title}</h5>`);
        }
    }



    /**
     * Sets the content of the body element.
     *
     * @param {string} content - The new content to be set.
     */
    function setContent(content) {
        body.html(content);
    }

    const modal = {
        container: {
            element: element,
            dialog: dialog,
            header: header,
            body: body,
            footer: footer,
            action: {
                positive: btnPositive,
                negative: btnNegative
            }
        },
        show: show,
        hide: hide,
        setAction: setAction,
        setTitle: setTitle,
        setContent: setContent
    }

    modal.on = function (event, callback) {
        $(modal.container.element).on(event, callback);
    }
    modal.off = function (event, callback) {
        $(modal.container.element).off(event, callback);
    }



    return modal;
}



const ObjectToast = {
    create: function (text = "", textColor = "#0000ff45", seconds = 3) {
        let unlimit = false;
        if (seconds == null) {
            seconds = 3;
            unlimit = true;
        }

        if (!this.enable) return;
        this.enable = false;
        setTimeout(() => this.enable = true, 400)

        let max = 5;
        let posY = 100;
        let toast = $(`<toast style='background: white; width: 100%; transition: 0.25s; max-width: 350px; box-shadow: 0 0 4px #00000044; padding: 15px 35px 15px 15px;position: fixed;top: 100vh;right: -3000px;border-radius: 0.3rem;z-index: 900;'>`)
        if (/\-/g.test(textColor)) {
            toast.addClass(textColor);
        } else {
            toast.css("color", textColor);
        }
        let icon = $(`<icon style='display: inline-flex;border: 1px solid;border-radius: 5rem;width: 1.75rem;height: 1.75rem;justify-content: center;align-items: center;transform: rotate(-15deg);margin: 0 10px 0 0;'><i class="fa-solid fa-exclamation"></i></icon>`)
        let message = $(`<message>${text}</message>`)
        let close = $(`<btn type='button' style="position: absolute;top: 0;right: 0;color: #ff000078;padding: 0.5rem;"><i class="fa-solid fa-x"></i></btn>`)
        let progress = $(`<div class="progress" style='position: absolute;overflow:hidden;width: 100%;height: 4px;left: 0;bottom: -1px;'><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuemax="100"></div></div>`)

        toast.append(icon, message, close, progress)

        let lastToasts = $("toast")
        if (lastToasts.length > max) {
            return;
        }



        for (let i = 0; i < lastToasts.length; i++) {
            let lastToast = lastToasts[i];
            posY += (lastToast.offsetHeight) + 10;
        }
        toast.css("top", posY + "px")


        $(document.body).append(toast)

        let progressbar = progress.find(".progress-bar");
        progressbar.css("transition", `${seconds}s`)
        setTimeout(() => {
            toast.css("right", 25 + "px")
            progressbar.css("width", "0%")
        }, 50)

        let delay = 1000 * seconds;
        if (!unlimit) {
            let timeOut = setTimeout(() => {
                toast.remove()
                this.control()
            }, delay)

            close.on("click", () => {
                toast.remove();
                clearTimeout(timeOut)
            })
        }

        close.on("click", () => {
            toast.remove();
        })
    },


    enable: true,


    control: function () {
        let lastToasts = $("toast")
        let posY = 100;
        for (let i = 0; i < lastToasts.length; i++) {
            let lastToast = $(lastToasts[i]);
            let Y = Number(lastToast.css("top").replace(/[^0-9]+/gi, ''))
            lastToast.css("top", posY + "px");
            posY = Y
        }
    }
}


const toast = function (text, seconds = 3, textColor = "#0000ff45") {
    return ObjectToast.create(text, textColor, seconds);
}

const http = {
    get: function (url, data, headers = {}) {

        const oUrl = new URL(url);

        if (data instanceof FormData) {

            for (let pair of data.entries()) {
                oUrl.searchParams.append(pair[0], pair[1]);
            }
        } else {
            // Add query parameters from the data object
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    oUrl.searchParams.append(key, data[key]);
                }
            }
        }

        return $.ajax({
            xhr: createXmlHttpRequest,
            url: oUrl.toString(),
            method: 'GET',
            processData: false,
            contentType: false,
            cache: false,
            headers: headers
        });
    },


    post: function (url, data, headers = {}) {
        let form = new FormData();
        if (data instanceof FormData) {
            form = data;
        } else {
            Object.keys(data).forEach(key => {
                form.append(key, data[key]);
            })
        }
        return $.ajax({
            xhr: createXmlHttpRequest,
            url: url,
            method: 'POST',
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            headers: headers
        })
    },


    put: function (url, data, headers = {}) {
        let form = new FormData();
        if (data instanceof FormData) {
            form = data;
        } else {
            Object.keys(data).forEach(key => {
                form.append(key, data[key]);
            })
        }
        return $.ajax({
            xhr: createXmlHttpRequest,
            url: url,
            method: 'PUT',
            data: form,
            processData: false,
            contentType: false,
            headers: Object.assign(headers, { 'X-HTTP-Method-Override': 'PUT' }),
            cache: false
        })
    },


    patch: function (url, data, headers = {}) {
        let form = new FormData();
        if (data instanceof FormData) {
            form = data;
        } else {
            Object.keys(data).forEach(key => {
                form.append(key, data[key]);
            })
        }
        return $.ajax({
            xhr: createXmlHttpRequest,
            url: url,
            method: 'PATCH',
            data: form,
            processData: false,
            contentType: false,
            headers: Object.assign(headers, { 'X-HTTP-Method-Override': 'PATCH' }),
        })
    },


    delete: function (url, data, headers = {}) {
        let form = new FormData();
        if (data instanceof FormData) {
            form = data;
        } else {
            Object.keys(data).forEach(key => {
                form.append(key, data[key]);
            })
        }
        return $.ajax({
            xhr: createXmlHttpRequest,
            url: url,
            method: 'DELETE',
            data: form,
            processData: false,
            contentType: false,
            headers: Object.assign(headers, { 'X-HTTP-Method-Override': 'DELETE' }),
        })
    }
}


const cookie = {
    set: function (name, value, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    },
    get: function (name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    delete: function (name) {
        cookie.set(name, "", -1);
    }
}


const dialog = {
    confirm: function (title, message) {

        const modal = createModal(title, message);

        return new Promise(resolve => {

            function confirmed() {
                resolve(true);
            }
            function cancelled() {
                resolve(false);
            }

            modal.setAction('+', {
                text: 'Yes',
                callback: () => {
                    resolve(true);
                    modal.hide();
                }
            })
            modal.setAction('-', {
                text: 'No',
                callback: () => {
                    resolve(false);
                    modal.hide();
                }
            })

            modal.show();
            modal.on("modal:hide", () => {
                setTimeout(() => resolve(false), 50);
            });
        });
    },

    confirmDanger: function (title, message) {
        const modal = createModal(title, message);

        return new Promise(resolve => {

            const positive = $(modal.container.action.positive);
            const negative = $(modal.container.action.negative);
            positive.addClass("btn btn-danger")
            positive.text("Continue")
            positive.on("click", () => {
                resolve(true);
                modal.hide();
            })
            negative.addClass("btn btn-primary")
            negative.removeClass("btn-secondary")
            negative.text("Cancel")
            negative.on("click", () => {
                resolve(false);
                modal.hide();
            })
            modal.show();

            modal.on("modal:hide", () => {
                setTimeout(() => resolve(false), 50);
            });
        });
    }
}

const assign = function (name, obj) {
    Object.assign(this, { [name]: obj });
}

$.ajax({
    error: (e) => {
        if (e.responseJSON && e.responseJSON.message) {
            toast(e.responseJSON.message, 10, 'text-' + (e.code >= 401 ? 'danger' : 'warning'));
        } else {
            toast(e.statusText || e.responseText, 10, 'text-' + (e.code >= 401 ? 'danger' : 'warning'));
        }
    }
})




export {
    assign,
    createModal,
    http,
    cookie,
    dialog,
    toast
};