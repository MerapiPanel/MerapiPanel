import { EditorView, basicSetup } from 'codemirror';
import { EditorState } from '@codemirror/state';
import { indentOnInput } from '@codemirror/language';
import { html } from '@codemirror/lang-html';
import { css } from '@codemirror/lang-css';
import { oneDark } from '@codemirror/theme-one-dark';
import beautify from 'js-beautify';
import { drawSelection, highlightActiveLine } from '@codemirror/view';

export default (editor, opts = {}) => {


    // After initializing GrapesJS
    editor.Panels.removeButton('options', 'export-template'); // This is for the export button, which includes code view
    // or
    editor.Panels.removeButton('views', 'open-code');


    $(document.head).append($(`<style>
    .code-edit-modal .gjs-mdl-dialog {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        max-width: unset;
        border-radius: 0;
    }
    .code-edit-modal .gjs-mdl-header {
        background: #1f1f1f;
    }
    .code-edit-modal .gjs-mdl-dialog .gjs-mdl-content, .code-edit-modal .gjs-mdl-dialog .gjs-mdl-content>div {
        height: inherit;
        padding: 0;
    }
    .code-parent {
        display: flex;
        width: 100%;
        height: 100%;
    }

    .divider-horizontal {
        width: 100%;
        max-width: 10px !important;
        background: #474747;
        cursor: ew-resize;
    }
    .divider-horizontal:hover {
        background-color: var(--edt-color-primary);
    }
    .divider-horizontal.active {
        background-color: var(--edt-color-primary);
    }
    .code-container {
        width: 100%;
        max-height: 300px;
        min-width: 30px !important;
        height: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background-color: #1f1f1f;
    }

    .code-head {
        background-color: #1f1f1f;
        padding: 2px 7px;
        display: flex;
    }
    .code-head .corner {
        min-width: 20px;
        min-height: 20px;
        background-color: #1f1f1f;
    }
    .code-head .code-title {
        transition: 0.3s;
        transform: rotate(0);
        transform-origin: top left;
        height: 20px;
        width: 100%;
        font-size: 14px;
    }
    .code-container.close { position: relative; }
    .code-container.close .code-title {
        transform: rotate(90deg);
     }
    .code-container.close .code-body { opacity: 0; }
    
    .code-body { transition: 0.3s; overflow: auto; opacity: 1; } 
    .code-body > div { min-height: 275px; min-width: max-content; }

    .code-body::-webkit-scrollbar-track {
        background: transparent !important; 
    }
    ::-webkit-scrollbar-track {
        background: #ffffff00; /* Change to your desired track color */
    }

    </style>`));


    editor.Panels.addButton("options", {
        id: "html-code-editor",
        className: "fa fa-code",
        attributes: {
            title: "Code"
        },
        command: "open-code-editor",
    })


    const container = $(`<div class='flex code-parent'>
        <div class="code-container">
            <div class="code-head">
                <div class="corner"></div>
                <h3 class='code-title'>HTML</h3>
            </div>
            <div class="code-body" id="html-code-editor"></div>
        </div>
        <div class="divider-horizontal"></div>
        <div class="code-container">
            <div class="code-head">
                <div class="corner"></div>
                <h3 class='code-title'>CSS</h3>
            </div>
            <div class="code-body" id="css-code-editor"></div>
        </div>
    </div>`);


    let htmlEditorElement = container.find('#html-code-editor')[0];
    let cssEditorElement = container.find('#css-code-editor')[0];
    let leftPane = container.find('.code-container')[0];
    let rightPane = container.find('.code-container')[1];
    let divider = container.find('.divider-horizontal')[0];
    let isDragging = false;

    divider.addEventListener('mousedown', function (e) {
        e.preventDefault(); // Prevents text selection or other default actions
        isDragging = true;
        let prevX = e.clientX;

        const onMouseMove = function (e) {
            if (!isDragging) return;

            const rect = container[0].getBoundingClientRect();
            if (rect.width === 0) {
                console.warn("Container width is 0, skipping resize");
                return; // Skip resizing as we cannot divide by zero
            }

            const deltaX = (e.clientX - prevX);
            prevX = e.clientX;

            const leftWidth = (leftPane.offsetWidth + deltaX);
            const rightWidth = (rightPane.offsetWidth - deltaX);


            // Check to avoid dividing by zero
            if (rect.width > 0) {
                var leftPaneWidth = `${((leftWidth / rect.width) * 100)}%`;
                var rightPaneWidth = `${((rightWidth / rect.width) * 100)}%`;

                // Set new widths as a percentage of the container width to maintain flexibility
                $(leftPane).css({ width: leftPaneWidth });
                $(rightPane).css({ width: rightPaneWidth });

                if (leftPane.offsetWidth < 40) {
                    $(leftPane).addClass('close');
                } else {
                    $(leftPane).removeClass('close');
                }
                if (rightPane.offsetWidth < 40) {
                    $(rightPane).addClass('close');
                } else {
                    $(rightPane).removeClass('close');
                }

            }
        };


        const onMouseUp = function () {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
            isDragging = false;
            $(divider).removeClass('active');
        };

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
        $(divider).addClass('active');
    });

    const extensions = [
        basicSetup,
        oneDark,
        indentOnInput(),
        drawSelection(true),
        highlightActiveLine(),
        EditorView.lineWrapping
    ];

    const htmlEditor = new EditorView({
        parent: htmlEditorElement,
        state: EditorState.create({
            doc: beautify.html('<html><head></head><body></body></html>'),
            extensions: [html()].concat(extensions),
        }),
    });
    const cssEditor = new EditorView({
        parent: cssEditorElement,
        state: EditorState.create({
            doc: beautify.css('* { box-sizing: border-box; }'),
            extensions: [css()].concat(extensions),
        })
    })



    editor.Commands.add("open-code-editor", {
        run: (editor, sender) => {





            // editor.runCommand('get-code-data', {
            //     callback: function (e) {
            //         console.log(e)
            //     }
            // });

            let htmlContent = beautify.html(cleanHtmlBody(editor.getHtml()));
            htmlEditor.setState(EditorState.create({
                doc: htmlContent,
                extensions: [
                    html(),
                    EditorView.updateListener.of((v) => {
                        if (v.docChanged) {
                            const content = htmlEditor.state.doc.toString();
                            editor.setComponents(content.trim());
                        }
                    }),
                ].concat(extensions),
            }));


            let cssContent = beautify.css(editor.getCss());
            cssEditor.setState(EditorState.create({
                doc: cssContent,
                extensions: [
                    css(),
                    EditorView.updateListener.of((v) => {
                        if (v.docChanged) {
                            const content = cssEditor.state.doc.toString();
                            editor.setStyle(content.trim());
                        }
                    }),
                ].concat(extensions),
            }))

            editor.Modal.open({
                title: 'Code Editor',
                content: container,
                attributes: {
                    class: 'code-edit-modal',
                },
            });

        }
    })


    function cleanHtmlBody(htmlString) {

        // Create a DOMParser instance
        const parser = new DOMParser();

        // Parse the HTML string
        const doc = parser.parseFromString(htmlString, 'text/html');

        // Remove all meta, link, and script elements
        doc.querySelectorAll('meta, link, script').forEach(element => {
            element.remove();
        });

        //        console.log(doc.body.outerHTML);
        // Get the cleaned HTML string
        return doc.body.outerHTML;
    }
}