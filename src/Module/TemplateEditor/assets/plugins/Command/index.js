
// import $ from "jquery";
// import merapi from "../../../../../base/assets/merapi";

const CommandPlugin = (editor, args = {}) => {

    /**
    * Modal command
    */
    const ModalManager = editor.Modal;
    editor.Commands.add('clear-confirm-modal', {
        run: function (editor, sender, options = {}) {
            sender && sender.set('active', 0); // turn off the button

            const content = $(
                `<div class='py-4'>
                     <div class='mb-3'>
                         <p>Are you sure you want to clear this canvas?</p>
                         <small>This will remove all components and styles from the canvas.<br><i>This action cannot be undone.</i></small>
                     </div>
                     <div class='flex'>
                         <button class='ms-auto btn btn-danger'>Clear</button>
                     </div>
                 </div>`);

            ModalManager.open({
                title: "<h4 class='text-xl font-bold text-danger'><i class=\"fa-solid fa-broom\"></i> Are you sure?</h4>",
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('.flex>.btn').on('click', function () {
                console.log("click")
                ModalManager.close();
                editor.DomComponents.clear(); // Clear components
                editor.CssComposer.clear();  // Clear styles
                editor.UndoManager.clear();
                editor.runCommand('clear-storage');
                merapi.toast('Action completed', 5, 'text-success');
            })
        }
    })

    editor.Commands.add('confirm-modal', {
        run: function (editor, sender, options = {
            title: null,
            text: null,
            callback: null
        }) {

            const content = $(
                `<div class='py-4'>
                     <div class='mb-3'>
                         <p>${options.text}</p>
                     </div>
                     <div class='flex'>
                         <button class='ms-auto btn btn-primary'>Yes</button>
                     </div>
                 </div>`);

            ModalManager.open({
                title: "<h4 class='text-xl font-bold'>" + options.title + "</h4>",
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('.btn-primary').on('click', function () {
                ModalManager.close();
                options.callback(ModalManager);
            })
        }
    })

    editor.Commands.add('open-save-modal', {

        createElement: function (param = {}) {

            const label = $(`<label for='${param.name}'>${param.required ? '<span class="text-danger font-bold" style="padding: 0 0.2rem 0 0.1rem; vertical-align: top;">*</span>' : ''}${param.label}</label>`);

            const elements = {
                input: (param) => {
                    let el = $(`<input class='text-input' type="text" name="${param.name}" id="${param.name}" placeholder="${param.placeholder || 'Type Here...'}" required="${param.required}">`)
                    if (param.value) {
                        el.val(param.value);
                    }
                    return el;
                },
                textarea: (param) => {
                    let el = $(`<textarea class='text-input' name="${param.name}" id="${param.name}" placeholder="${param.placeholder || 'Type Here...'}" required="${param.required}"></textarea>`)
                    if (param.value) {
                        el.val(param.value);
                    }
                    return el;
                },
                select: (param) => {
                    let el = $(`<select class='text-input' name="${param.name}" id="${param.name}" required="${param.required}"></select>`);
                    if (param.options) {
                        for (let i in param.options) {
                            el.append(`<option value="${param.options[i]}">${param.options[i]}</option>`);
                        }
                    }
                    return el;
                }
            }

            const wrapper = $(`<div class='mb-3'></div>`);
            wrapper.append(label);
            if (elements[param.type]) {
                let element = elements[param.type](param);
                if (!param.required) {
                    element.removeAttr('required');
                }
                wrapper.append(element);

            } else {
                wrapper.append($(`<input class='text-input' type="text" name="${param.name}" value="${param.value || ''}" id="${param.name}" disabled/>`))
            }


            return wrapper;
        },

        run: function (editor, sender, args = {}) {

            Object.assign({
                params: [],
                callback: null
            }, args);

            const params = args.params || [];
            const content = $(`<div class='py-4'><div class='mb-3' id="container"></div><div class='flex'><button class='ms-auto btn btn-primary'>Save</button></div></div>`);

            for (let i in params) {

                const param = Object.assign({}, params[i]);
                const element = this.createElement(param);
                $(element.find(`#${param.name}`)).on('change', function (e) {

                    if (!$(e.target).prop('disabled')) {
                        param.value = e.target.value;
                    }
                    params[i]['value'] = e.target.value;
                })
                content.find('#container').append(element);

            }

            ModalManager.open({
                title: 'Save Changes',
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('.btn-primary').on('click', function () {

                for (let i in params) {
                    if (params[i].required && (params[i].value == '' || params[i].value == null)) {
                        merapi.toast(`${params[i].label} fields are required`, 5, 'text-warning');
                        content.find(`#${params[i].name}`).focus();
                        return;
                    }
                }

                if (args.callback) {
                    args.callback(params.map((param) => {
                        return {
                            name: param.name,
                            value: param.value || ''
                        }
                    }));
                    ModalManager.close();
                }
            })
        }
    })


    editor.Commands.add('call-endpoint', {
        run: function (editor, sender, options = {}) {

            Object.assign({
                token: null,
                endpoint: null,
                params: [],
            }, options);


            if (!options.endpoint) {
                merapi.toast('Save endpoint is not defined', 5, 'text-warning');
                return;
            }
            if (!options.token) {
                merapi.toast('Save token is not defined', 5, 'text-warning');
                return;
            }


            editor.runCommand('get-html-twig', {
                callback: (twig) => {

                    editor.store();

                    sendToEndpoint({
                        endpoint: options.endpoint,
                        htmldata: twig,
                        cssdata: editor.getCss(),
                        params: options.params,
                        callback: options.callback,
                        token: options.token

                    })
                }
            });
        }
    });


    function sendToEndpoint(args = {}) {

        Object.assign({}, {
            endpoint: null,
            token: null,
            params: [],
            htmldata: null,
            cssdata: null,
            callback: null
        }, args);

        var form = new FormData();
        form.append("htmldata", args.htmldata);
        form.append("cssdata", args.cssdata);
        form.append("m-token", args.token);

        for (let i in args.params) {
            form.append(args.params[i].name, args.params[i].value);
        }

        merapi.http.post(args.endpoint, form).then(function (data, textStatus, xhr) {

            if (xhr.status === 200) {

                merapi.toast(data.message, 5, 'text-success');
                editor.runCommand('clear-storage');
                if (args.callback) {
                    args.callback(data.data);
                }

            } else throw new Error(textStatus, xhr.status);

        }).catch(function (error) {

            console.error(error);
            merapi.toast(error.message ?? error, 5, 'text-warning');
        })
    }

}

export default CommandPlugin;