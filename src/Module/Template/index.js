import grapesjs from "grapesjs";
import gjsBasic from 'grapesjs-blocks-basic';
import gjsForms from 'grapesjs-plugin-forms';
import tailwind from 'grapesjs-tailwind';
import $ from "jquery";
import merapi from '../../merapi';


$(document).on('DOMContentLoaded', function () {

    var template_name = "";
    var template_description = "";

    var editor = grapesjs.init({
        fromElement: true,
        container: '#gjs',
        height: '100vh',
        components: '<div class="example"><h1 class="text-3xl font-bold text-blue-500">Welcome Les\'s Get Started</h1></div>',
        style: '.example { width: 100vh; height: 100vw; display: flex; align-items: center; justify-content: center; }',
        plugins: [tailwind, gjsBasic, gjsForms],
        pluginsOpts: {
            [gjsBasic]: { /* options */ },
            [gjsForms]: { /* options */ },
            [tailwind]: {}
        },
    });


    editor.Panels.addButton('options',
        [{
            id: 'save',
            className: 'fas fa-save',
            command: 'open-save-modal',
            attributes: {
                title: 'Save Changes'
            }
        }]
    );


    const modal = editor.Modal;

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

    editor.Commands.add('commsave', {
        run: function (editor, sender, options = {}) {


            if (!options.name || options.name.length == 0) {

                merapi.toast('Template Name is required', 5, 'warning');
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
            form.append("template['htmldata']", htmldata);
            form.append("template['cssdata']", cssdata);
            form.append("template['tailwindcss']", tailwindcss);

            merapi.post("", form).then(function (data) {

                if (typeof data.response !== 'string') {
                    merapi.toast(data.response, 5, 'red');
                } else {
                    merapi.toast(data.response.message, 5, 'green');
                }
            }).catch(function (error) {
                merapi.toast(`${error.status} ${error.statusText}`, 5, 'red');
            })
        }
    });
});

