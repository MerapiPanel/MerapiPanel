// import $ from "jquery";
import grapesjs from "grapesjs";
import gjsBasic from 'grapesjs-blocks-basic';
// import gjsForms from 'grapesjs-plugin-forms';
// import gjsTailwind from "grapesjs-tailwind";
import ModuleLoader from "./plugins/moduleLoader/index.js";
import AssetPlugin from "./plugins/Asset/index.js";
import StoragePlugin from "./plugins/Storage/index.js";
import CommandPlugin from "./plugins/Command/index.js";
import 'grapesjs/dist/css/grapes.min.css';
import codeEdit from "./plugins/codeEdit/index.js";
// import Merapi from "../../../base/assets/merapi.js";



const ControlPanel = (editor, args = {}) => {


    Object.assign({
        editor: {
            holder: null,
            id: null,
            params: []
        },
        plugins: []
    }, args);


    // for (let i in args.plugins) {
    //     let plugin = args.plugins[i];
    //     editor.
    // }
    editor.Panels.addButton('options',
        [{
            id: 'save',
            className: 'fas fa-save',
            command: {
                run: (editor) => {
                    editor.runCommand('open-save-modal', {
                        params: args.editor.params,
                        callback: (response) => {
                            editor.runCommand('call-endpoint', {
                                endpoint: args.editor.endpoint.save,
                                token: args.token,
                                params: response,
                                callback: (response) => {

                                    const params = response.params;

                                    args.editor.params.forEach((param) => {
                                        param.value = params[param.name] || param.value;
                                        delete params[param.name];
                                    });

                                    if (Object.keys(params).length > 0) {
                                        for (let x in params) {
                                            let val = params[x];
                                            args.editor.params.push({
                                                label: x,
                                                name: x,
                                                value: val
                                            })
                                        }
                                    }

                                    if (response.state) {

                                        const state = Object.assign({}, {
                                            data: {},
                                            title: '',
                                            url: null
                                        }, response.state);

                                        window.history.replaceState(state.data, state.title, state.url);
                                    }
                                }
                            });
                        }
                    });
                }
            },
            attributes: {
                title: 'Save Changes'
            },
            args: args
        }]
    );
    editor.Panels.addButton('options', [
        {
            id: 'clear',
            className: 'fa-solid fa-broom',
            command: 'clear-confirm-modal',
            attributes: {
                title: 'Clear Template'
            }
        }
    ]);
}




const initEditor = (args = {}) => {

    Object.assign({
        token: null,
        editor: {
            holder: null,
            id: null,
            params: [
                {
                    type: "input",
                    label: "Text",
                    name: "text",
                    required: true
                },
                {
                    type: "textarea",
                    label: "Description",
                    name: "description",
                    required: false
                }
            ],
            endpoint: {
                fetch: null,
                script: null,
                save: null
            }
        },
        assets: {
            url: null,
            name: null,
            upload: null
        },
        component: {
            fetch: null,
            render: null
        }
    }, args);


    $(document.head).append(`<style>
    :root {
        --edt-color-primary: #64b7fe;
        --gjs-quaternary-color: #64b7fe;
    }
    .gjs-four-color { color: var(--edt-color-primary); }
    </style>`);


    if (!args.editor.holder) {

        merapi.toast('Holder for editor mount is not defined', null, 'text-danger');
        return;
    }


    /**
     * GrapesJS init
     */
    var editor = grapesjs.init({
        fromElement: true,
        container: '#gjs',
        height: '100vh',
        protectedCss: '',
        plugins: [ModuleLoader, codeEdit, gjsBasic, AssetPlugin, StoragePlugin, CommandPlugin],
        pluginsOpts: {
            [ModuleLoader]: {
                endpoint: {
                    fetch: args.component.endpoint.fetch,
                },
                token: args.token
            },
            [AssetPlugin]: {
                url: args.assets.url,
                name: args.assets.name,
                upload: args.assets.upload
            },
            [StoragePlugin]: args.editor,
            [CommandPlugin]: {
                id: args.editor.id,
                params: args.editor.params
            }
        },
        storageManager: {
            autoload: false,
            options: {
                local: { key: `editing${args.editor.id ? '-' + args.editor.id : ''}` }
            }
        },
        commands: {

        },
    });

    // initial cutom control to control grapesjs editor
    ControlPanel(editor, args);


    /**
     * load template initial scripts
     */
    merapi.http.get(args.editor.endpoint.script).then(function (data, textStatus, xhr) {
       
        const cssData = data.data.css;
        const jsData = data.data.js;

        for (let x = 0; x < cssData.length; x++) {
            const css = cssData[x];
            $(editor.Canvas.getDocument().head).append($(`<link rel="stylesheet" href="${css}">`));
        }
        for (let x2 = 0; x2 < jsData.length; x2++) {
            const js = jsData[x2];
            $(editor.Canvas.getDocument().body).append($(`<script src="${js}"></script>`));
        }
    })
}




merapi.assign('editor', {
    init: initEditor
});