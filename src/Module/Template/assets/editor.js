import grapesjs from "grapesjs";
import gjsBasic from 'grapesjs-blocks-basic';
import gjsForms from 'grapesjs-plugin-forms';
import tailwind from 'grapesjs-tailwind';
import $ from "jquery";
import merapi from "../../../base/assets/merapi.js";

function templateEditor(options = {
    id: null,
    name: null,
    description: null,
    url: null,
    save: null,
    assets: {
        url: null,
        name: null,
        upload: null
    }
}) {

    var flg = 0;
    if (!options.assets) {
        merapi.toast('assets option is not prepared', 5, 'text-danger'); flg++;
    } else {

        if (!options.assets.url) {
            merapi.toast('assets url is empty', 5, 'text-danger'); flg++;
        }
        if (!options.assets.name) {
            merapi.toast('assets name is empty', 5, 'text-danger'); flg++;
        }
        if (!options.assets.upload) {
            merapi.toast('assets upload is empty', 5, 'text-danger'); flg++;
        }
    }

    if (flg > 0) {

        $('#gjs').html(`<h1>Error some important option is assumed</h1>`);
        $('#gjs').find('h1').css({
            color: 'red',
            position: 'fixed',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            fontSize: '2rem',
            fontWeight: 'bold',
            textAlign: 'center'
        });
        return;
    }

    var template_save = options.save || null;
    var template_id = options.id || null;
    var template_name = options.name || "";
    var template_description = options.description || "";
    var template_url = options.url || null;

    var editor = grapesjs.init({
        fromElement: true,
        container: '#gjs',
        height: '100vh',
        plugins: [tailwind, gjsBasic, gjsForms],

        pluginsOpts: {
            [gjsBasic]: { /* options */ },
            [gjsForms]: { /* options */ },
            [tailwind]: {}
        },

        storageManager: {
            autoload: false
        },

        assetManager: {
            upload: 'https://endpoint/upload/assets',
            uploadName: 'files',
        }
    });


    if (template_url) {
        $.get(template_url, function (e) {
            editor.setComponents(e);
            merapi.toast('Template Loaded', 5, 'text-success');
        })
    }


    /** 
     * Asset Manager Group
     */
    const am = editor.AssetManager;
    const amConfig = am.getConfig();

    am.clear();
    $.get(options.assets.url, function (e) {
        if (e.data) {
            let assets = e.data.map(function (item) {
                return {
                    src: item.path,
                    category: item.parent
                }
            })
            am.add(assets);
        }
    })

    amConfig.upload = options.assets.upload;
    amConfig.uploadName = options.assets.name;

    // The upload is started
    editor.on('asset:upload:start', () => {

        console.log('start');
    });

    // The upload is ended (completed or not)
    editor.on('asset:upload:end', () => {

        console.log('end');
    });

    // Error handling
    editor.on('asset:upload:error', (err) => {

        console.log(err);
    });

    // Do something on response
    editor.on('asset:upload:response', (response) => {

        console.log(response);
    });

    /**
     * Panels action button
     */
    editor.Panels.addButton('options',
        [{
            id: 'save',
            className: 'fas fa-save text-primary',
            command: 'open-save-modal',
            attributes: {
                title: 'Save Changes'
            }
        }]
    );
    editor.Panels.addButton('options', [
        {
            id: 'clear',
            className: 'fas fa-trash text-danger',
            command: 'clear-confirm-modal',
            attributes: {
                title: 'Clear Template'
            }
        }
    ]);


    /**
     * Modal command
     */
    const modal = editor.Modal;
    editor.Commands.add('clear-confirm-modal', {
        run: function (editor, sender, options = {}) {
            sender && sender.set('active', 0); // turn off the button

            const content = $(
                `<div class='py-4'>
                    <div class='mb-3'>
                        <p>Are you sure you want to clear this template?</p>
                    </div>
                    <div class='flex'>
                        <button class='ms-auto btn btn-primary'>Yes</button>
                    </div>
                </div>`);

            modal.open({
                title: "<h4 class='text-xl font-bold'><i class=\"fas fa-trash me-3\"></i> Are you sure?</h4>",
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('.btn-primary').on('click', function () {
                modal.close();
                editor.DomComponents.clear(); // Clear components
                editor.CssComposer.clear();  // Clear styles
                editor.UndoManager.clear();
                merapi.toast('Template Cleared', 5, 'text-success');
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

            modal.open({
                title: "<h4 class='text-xl font-bold'>" + options.title + "</h4>",
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('.btn-primary').on('click', function () {
                modal.close();
                options.callback(modal);
            })
        }
    })

    editor.Commands.add('open-save-modal', {
        run: function (editor, sender, options = {}) {

            // sender && sender.set('active', 0); // turn off the button

            const content = $(
                `<div class='py-4'>
                <div class='mb-3'>
                    <label class='mb-3'>Template Name</label>
                    <input class='text-input' type="text" name="template_name" id="template_name" placeholder="Enter Template Name" value="${template_name}">
                </div>
                <div class='mb-3'>
                    <label class='mb-3'>Template Description</label>
                    <textarea class='text-input' name="template_description" id="template_description" placeholder="Enter Template Description">${template_description}</textarea>
                </div>
                <div class='flex'>
                    <button class='ms-auto btn btn-primary'>Save</button>
                </div>
            </div>`);

            modal.open({
                title: 'Save Template',
                content: content.get(0),
                attributes: { class: 'small-modal', style: 'backdrop-filter: blur(3px);' },
            });
            content.find('#template_name').on('input', function () {
                template_name = content.find('#template_name').val();
            })
            content.find('#template_description').on('input', function () {
                template_description = content.find('#template_description').val();
            })
            content.find('.btn-primary').on('click', function () {

                modal.close();

                const template_name = content.find('#template_name').val();
                const template_description = content.find('#template_description').val();

                editor.runCommand('commsave', {

                    name: template_name,
                    description: template_description

                });
            })
        }
    })



    /**
     * Other command
     */

    editor.Commands.add('commsave', {
        run: function (editor, sender, options = {}) {

            if (!template_save) {

                merapi.toast('Save endpoint is not defined', 5, 'text-warning');
                return;
            }

            if (!options.name || options.name.length == 0) {

                merapi.toast('Template Name is required', 5, 'text-warning');
                editor.runCommand('open-save-modal');
                return;
            }

            editor.store();
            //storing values to variables
            var htmldata = editor.getHtml();
            var cssdata = editor.getCss();
            var tailwindcss = "";

            editor.runCommand('get-tailwindCss', {
                callback(e) {
                    tailwindcss = e;
                }
            });


            var form = new FormData();
            form.append("name", options.name);
            form.append("description", options.description);
            form.append("htmldata", htmldata);
            form.append("cssdata", tailwindcss);
            // form.append("template['tailwindcss']", tailwindcss);
            if (template_id) form.append("id", template_id);

            merapi.post(template_save, form).then(function (data, textStatus, xhr) {

                if (xhr.status === 200 && data.data.id) {

                    template_id = data.data.id;
                    merapi.toast(data.message, 5, 'text-success');

                    window.localStorage.removeItem('gjsProject');

                } else throw new Error(textStatus, xhr.status);

            }).catch(function (error) {

                merapi.toast(error.message ?? error, 5, 'text-warning');
            })
        }
    });


    /**
   * Storage Manager
   */
    if (window.localStorage.getItem('gjsProject') && template_id == null) {

        setTimeout(() => {

            editor.runCommand('confirm-modal', {
                title: 'Load last progress?',
                text: "Do you want to load last progress?",
                callback: () => {
                    editor.loadProjectData(JSON.parse(window.localStorage.getItem('gjsProject')));
                    merapi.toast('Last progress loaded', 5, 'text-success');
                }
            })
        }, 500);
    }

    $(window).on('beforeunload', function () {
        return 'Are you sure you want to leave?';
    });
}

window.merapi = window.merapi || {};
window.merapi.templateEditor = templateEditor;