import $ from "jquery";
import grapesjs from "grapesjs";

import gjsBasic from 'grapesjs-blocks-basic';
import gjsForms from 'grapesjs-plugin-forms';
import gjsTailwind from "grapesjs-tailwind";
import ModuleLoader from "./plugins/moduleLoader/index.js";
import AssetPlugin from "./plugins/Asset/index.js";
import StoragePlugin from "./plugins/Storage/index.js";
import CommandPlugin from "./plugins/Command/index.js";

import 'grapesjs/dist/css/grapes.min.css';
import Merapi from "../../../base/assets/merapi.js";





const ControlPanel = (editor, args = {}) => {

    editor.Panels.addButton('options',
        [{
            id: 'save',
            className: 'fas fa-save',
            command: {
                run: (editor) => {
                    editor.runCommand('open-save-modal', {
                        params: args.params,
                        callback: (response) => {
                            editor.runCommand('call-endpoint', {
                                endpoint: args.endpoint,
                                params: response,
                                callback: (response) => {

                                    const params = response.params;

                                    args.params.forEach((param) => {
                                        param.value = params[param.name] || param.value;
                                        delete params[param.name];
                                    });
                                    
                                    if (Object.keys(params).length > 0) {
                                        for (let x in params) {
                                            let val = params[x];
                                            args.params.push({
                                                label: x,
                                                name: x,
                                                value: val
                                            })
                                        }
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

    const option = Object.assign({}, {
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
        endpoint: null,
        assets: {
            url: null,
            name: null,
            upload: null
        }
    }, args);

    if (!option.holder) {

        Merapi.toast('Holder for editor mount is not defined', null, 'text-danger');
        return;
    }


    /**
     * GrapesJS init
     */
    var editor = grapesjs.init({
        fromElement: true,
        container: '#gjs',
        height: '100vh',
        plugins: [ModuleLoader, gjsBasic, gjsForms, gjsTailwind, AssetPlugin, StoragePlugin, CommandPlugin],
        pluginsOpts: {
            [AssetPlugin]: {
                url: option.assets.url,
                name: option.assets.name,
                upload: option.assets.upload
            },
            [StoragePlugin]: option,
            [CommandPlugin]: {
                id: option.id,
                params: option.params
            }
        },
        storageManager: {
            autoload: true,
            options: {
                local: { key: `editing${args.id ? '-' + args.id : ''}` }
            }
        },
        commands: {

        }
    });

    ControlPanel(editor, option);
}




const Editor = {
    init: initEditor
}
Merapi.assign('Editor', Editor);