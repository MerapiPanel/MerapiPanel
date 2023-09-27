import CodeMirror from 'codemirror';
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/ambiance.css';
import 'codemirror/mode/php/php.js';
import $ from 'jquery';
import { trimEnd } from 'lodash';
import Merapi from '../../../base/assets/merapi';

const CodeBlock = function (Editor) {


    const customCodeType = {

        // Make the editor understand when to bind `custom-code-type`
        isComponent: function (el) {
            return el.tagName === 'PHPCODE';
        },

        // Model definition
        model: {
            // Default properties
            defaults: {
                tagName: 'phpcode',
                droppable: false, // Can't drop other elements inside
                style: {
                    display: "block",
                    padding: "10px",
                    border: "3px red dashed"
                },
                code: "<?php\r\necho \"Hello, world!\";\r\n?>",
                traits: []
            },

            init() { },

            updated(property, value, prevValue) {

            },

            codeEdit() {

                const content = $(`<div><textarea class="mb-3" id="ccode">${this.attributes.code}</textarea><div class="flex pt-3"><button class='ms-auto btn btn-primary'>save</button></div></div>`);
                const CodeEditor = CodeMirror.fromTextArea($(content).find("#ccode").get(0), {
                    mode: "application/x-httpd-php",
                    lineNumbers: true,
                    matchBrackets: true,
                    theme: "ambiance",
                    lineWiseCopyCut: true,
                    autoRefresh: true,
                    undoDepth: 200,
                    autoBeautify: true,
                    autoCloseTags: true,
                    autoCloseBrackets: true,
                    styleActiveLine: true,
                    smartIndent: true, // Missing comma
                    indentWithTabs: true, // Remove trailing comma just to be safe
                });

                Editor.Modal.open({
                    title: 'PHP Code',
                    content: content[0],
                });

                CodeEditor.refresh();

                $(content).find("button").on("click", () => {
                    this.saveCode(CodeEditor.doc.getValue());
                })
            },

            phprender() {

                const endpoint = trimEnd(decodeURIComponent(Merapi.getCookie("fm-adm-pth")), "/") + "/codeblock/static-compile";
                const form = new FormData();
                form.append("code", this.attributes.code);

                Merapi.post(endpoint, form).then(res => {
                    if (res.data.output) {
                        this.view.el.innerHTML = res.data.output;
                    }
                }).catch(err => {
                    this.view.el.innerHTML = err.responseText || err;
                })
            },

            saveCode(value) {

                this.attributes.code = value;
                Editor.store();
                this.phprender();
            }
        },

        view: {
            init() {
                $(this.el).on("dblclick", () => {
                    this.model.codeEdit();
                });

                this.model.phprender();

                Editor.on('run:core:preview', () => {
                    $(this.el).css({
                        "display": "unset",
                        "padding": "unset",
                        "border": "unset"
                    })
                });

                Editor.on('stop:core:preview', () => {
                    $(this.el).css({
                        "display": "block",
                        "padding": "10px",
                        "border": "3px red dashed"
                    })
                });
            }
        },

    }

    Editor.DomComponents.addType('php-code', customCodeType);


    const bm = Editor.BlockManager;
    bm.add('php-code', {
        label: 'PHP Code',
        content: {
            type: 'php-code',
            style: {
                display: "block",
                padding: "10px",
                border: "3px red dashed"
            },
        },
        category: 'Code',
    });
}

export default CodeBlock;