import 'grapesjs/dist/css/grapes.min.css';
import "./editor/style.scss";
import grapesjs from 'grapesjs';


const MP_Editor = {
    init: function (options) {

        var element, editor;

        Object.defineProperty(this, "element", {
            get: () => element[0],
            set: (value) => {
                element = $(value);
            }
        });
        Object.defineProperty(this, "editor", {
            get: () => editor,
        })

        // set element fisrt
        this.element = $(options.selector);

        // init grapesjs
        editor = grapesjs.init({
            container: this.element,
            fromElement: true,
            height: '100%',

            storageManager: false,

            deviceManager: {
                devices: [{
                    name: 'Desktop',
                    width: '1280px', // default size
                }, {
                    name: 'Tablet',
                    width: '768px', // this value will be used on canvas width
                    widthMedia: '1024px', // this value will be used in CSS @media
                }, {
                    name: 'Mobile',
                    width: '320px', // this value will be used on canvas width
                    widthMedia: '480px', // this value will be used in CSS @media
                }]
            },

            layerManager: {
                appendTo: '.layers-container',
                showAllButton: true
            },

            panels: {
                defaults: [
                    {
                        id: 'panel-left',
                        el: '.panel__left',
                        resizable: {
                            maxDim: 350,
                            minDim: 200,
                            tc: 0, // Top handler
                            cl: 1, // Left handler
                            cr: 0, // Right handler
                            bc: 0, // Bottom handler
                            // Being a flex child we need to change `flex-basis` property
                            // instead of the `width` (default)
                            keyWidth: 'flex-basis',
                        }
                    },
                    {
                        id: 'panel-right',
                        el: '.panel__right',
                    },
                    // ...
                    {
                        id: 'panel-switcher',
                        el: '.panel__switcher',
                        buttons: [{
                            id: 'show-style',
                            active: true,
                            label: '<i class="fa-solid fa-paint-roller"></i>',
                            command: 'show-styles',
                            togglable: false
                        },
                        {
                            id: 'show-traits',
                            active: true,
                            label: '<i class="fa-solid fa-gears"></i>',
                            command: 'show-traits',
                            togglable: false,
                        }],
                    },
                    {
                        id: 'panel-devices',
                        el: '.panel__devices',
                        buttons: [{
                            id: 'device-desktop',
                            label: '<i class="fa-solid fa-desktop"></i>',
                            command: 'set-device-desktop',
                            active: true,
                            togglable: false,
                        },
                        {
                            id: 'device-tablet',
                            label: '<i class="fa-solid fa-tablet"></i>',
                            command: 'set-device-tablet',
                            togglable: false,
                            togglable: false,
                        },
                        {
                            id: 'device-mobile',
                            label: '<i class="fa-solid fa-mobile"></i>',
                            command: 'set-device-mobile',
                            togglable: false,
                        }],
                    }
                ]
            },
            // The Selector Manager allows to assign classes and
            // different states (eg. :hover) on components.
            // Generally, it's used in conjunction with Style Manager
            // but it's not mandatory
            selectorManager: {
                appendTo: '.styles-container'
            },
            styleManager: {
                appendTo: '.styles-container',
                sectors: [{
                    name: 'Dimension',
                    open: false,
                    // Use built-in properties
                    buildProps: ['width', 'min-height', 'padding'],
                    // Use `properties` to define/override single property
                    properties: [
                        {
                            // Type of the input,
                            // options: integer | radio | select | color | slider | file | composite | stack
                            type: 'integer',
                            name: 'The width', // Label for the property
                            property: 'width', // CSS property (if buildProps contains it will be extended)
                            units: ['px', '%'], // Units, available only for 'integer' types
                            defaults: 'auto', // Default value
                            min: 0, // Min value, available only for 'integer' types
                        }
                    ]
                }, {
                    name: 'Extra',
                    open: false,
                    buildProps: ['background-color', 'box-shadow', 'custom-prop'],
                    properties: [
                        {
                            id: 'custom-prop',
                            name: 'Custom Label',
                            property: 'font-size',
                            type: 'select',
                            defaults: '32px',
                            // List of options, available only for 'select' and 'radio'  types
                            options: [
                                { value: '12px', name: 'Tiny' },
                                { value: '18px', name: 'Medium' },
                                { value: '32px', name: 'Big' },
                            ],
                        }
                    ]
                }]
            },
            traitManager: {
                appendTo: '.traits-container',
            },
        });

        editor.Panels.addPanel({
            id: 'panel-top',
            el: '.panel__top',
        });

        editor.Panels.addPanel({
            id: 'basic-actions',
            el: '.panel__basic-actions',
            buttons: [
                {
                    id: 'visibility',
                    active: true, // active by default
                    className: 'btn-toggle-borders',
                    label: '<i class="fa-solid fa-border-top-left"></i>',
                    command: 'sw-visibility', // Built-in command
                }, {
                    id: 'export',
                    className: 'btn-open-export',
                    label: '<i class="fa-solid fa-code"></i>',
                    command: 'export-template',
                    context: 'export-template', // For grouping context of buttons from the same panel
                }, {
                    id: 'show-json',
                    className: 'btn-show-json',
                    label: 'JSON',
                    context: 'show-json',
                    command(editor) {
                        editor.Modal.setTitle('Components JSON')
                            .setContent(`<textarea style="width:100%; height: 250px;">
                      ${JSON.stringify(editor.getComponents())}
                    </textarea>`)
                            .open();
                    },
                }
            ],
        });


        // Define commands
        editor.Commands.add('show-layers', {
            getRowEl(editor) { return editor.getContainer().closest('.editor-row'); },
            getLayersEl(row) { return row.querySelector('.layers-container') },

            run(editor, sender) {
                const lmEl = this.getLayersEl(this.getRowEl(editor));
                lmEl.style.display = '';
            },
            stop(editor, sender) {
                const lmEl = this.getLayersEl(this.getRowEl(editor));
                lmEl.style.display = 'none';
            },
        });
        editor.Commands.add('show-styles', {

            getRowEl(editor) { return editor.getContainer().closest('.editor-row'); },
            getStyleEl(row) { return row.querySelector('.styles-container') },

            run(editor, sender) {
                const smEl = this.getStyleEl(this.getRowEl(editor));
                smEl.style.display = '';
            },
            stop(editor, sender) {
                const smEl = this.getStyleEl(this.getRowEl(editor));
                smEl.style.display = 'none';
            },
        });

        editor.Commands.add('show-traits', {
            getTraitsEl(editor) {
                const row = editor.getContainer().closest('.editor-row');
                return row.querySelector('.traits-container');
            },
            run(editor, sender) {
                this.getTraitsEl(editor).style.display = '';
            },
            stop(editor, sender) {
                this.getTraitsEl(editor).style.display = 'none';
            },
        });

        // Commands
        editor.Commands.add('set-device-desktop', {
            run: editor => editor.setDevice('Desktop')
        });
        editor.Commands.add('set-device-tablet', {
            run: editor => editor.setDevice('Tablet')
        })
        editor.Commands.add('set-device-mobile', {
            run: editor => editor.setDevice('Mobile')
        });

    }
}




window.MP_Editor = MP_Editor;