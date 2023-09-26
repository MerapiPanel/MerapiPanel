import CodeMirror from 'codemirror';
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/ambiance.css';
import 'codemirror/mode/php/php.js';
import $ from 'jquery';

const CodeBlock = function (Editor) {


    const customCodeType = {

        // Make the editor understand when to bind `custom-code-type`
        isComponent: function (el) {
            return el.tagName === 'CUSTOMCODE';
        },

        // Model definition
        model: {
            // Default properties
            defaults: {
                tagName: 'customcode',
                droppable: false, // Can't drop other elements inside
                code: '<?php\r\necho "Hello, world!";\r\n?>',
                traits: [{
                    name: 'code',
                    changeProp: 1,
                }
                ]
            },
            init() {
                Editor.on('change:code', this.handleValueChange);
            },

            updated(property, value, prevValue) {
                if (value === "selected") {
                    this.codeEdit();
                }
            },

            codeEdit() {

                if (!this.isOnWatching) {
                    this.watching();
                }

                const content = $(`<div><textarea class="mb-3" id="ccode">${this.defaults.code}</textarea></div>`);
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
                    //lineWrapping: true,
                    styleActiveLine: true,
                    smartIndent: true, // Missing comma
                    indentWithTabs: true, // Remove trailing comma just to be safe
                });

                CodeEditor.on("change", () => {
                    this.defaults.code = CodeEditor.doc.getValue();
                });

                Editor.Modal.open({
                    title: 'Custom Code',
                    content: content[0],
                });

                CodeEditor.refresh();
            },

            watching() {

                this.isOnWatching = true;
                const ModalEl = Editor.Modal.modal.$el;
                (new MutationObserver((mutations) => {
                    Editor.selectRemove(this);
                })).observe(ModalEl[0], {
                    attributes: true
                });
            }
        },

        view: {},
    }

    Editor.DomComponents.addType('custom-code', customCodeType);

    const bm = Editor.BlockManager;

    bm.add('custom-code', {
        label: 'Custom Code',
        content: {
            type: 'custom-code',
            content: 'Hello, world!',
            style: { padding: '10px', backgroud: 'black' },
        },
        category: 'Basic',
        attributes: {
            customCode: 'Insert ...',
        },
    });
}

$(document).on("DOMContentLoaded", function () {

    $("customcode").each(function () {

        let $this = $(this);

        $this.on("click", function () {
            console.log('block', $this);
        })
    })

})



export default CodeBlock;