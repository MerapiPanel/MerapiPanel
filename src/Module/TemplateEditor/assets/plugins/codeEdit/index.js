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
        width: 15px;
        max-width: 15px;
        background: #474747;
        cursor: ew-resize;
    }
    .divider-horizontal:hover {
        background-color: var(--edt-color-primary);
    }
    
    .code-wraped {
        width: 100%;
        max-height: 300px;
        height: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .code-wraped .code-title {
        background-color: #1f1f1f;
        padding: 2px 10px;
    }
    .code-wraped .CodeMirror {
        min-width: max-content;
    }
    .CodeMirror-gutters {
        background-color: #1f1f1f !important;
    }
    .CodeMirror-scroll {
        background: radial-gradient(circle, rgba(2,0,36,1) 0%, rgba(0,0,0,1) 0%, rgba(31,31,31,1) 100%);
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


    const htmlCodeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
    const cssCodeViewer = editor.CodeManager.getViewer('CodeMirror').clone();

    htmlCodeViewer.set({
        codeName: 'htmlmixed',
        readOnly: 0,
        theme: 'hopscotch',
        autoBeautify: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        lineWrapping: true,
        styleActiveLine: true,
        smartIndent: true,
        indentWithTabs: true
    });

    cssCodeViewer.set({
        codeName: 'css',
        readOnly: 0,
        theme: 'hopscotch',
        autoBeautify: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        lineWrapping: true,
        styleActiveLine: true,
        smartIndent: true,
        indentWithTabs: true
    });


    var container = $(`
    <div class='flex code-parent'>
    <div class="code-wraped">
        <h3 class='code-title'>HTML</h3>
        <textarea id="html-code-editor"></textarea>
    </div>
    <div class="divider-horizontal"></div>
    <div class="code-wraped">
        <h3 class='code-title'>CSS</h3>
        <textarea id="css-code-editor"></textarea>
        </div>
    </div>`);


    let leftPane = container.find('.code-wraped')[0];
    let rightPane = container.find('.code-wraped')[1];
    let divider = container.find('.divider-horizontal')[0];
    let isDragging = false;

    divider.addEventListener('mousedown', function (e) {
        e.preventDefault(); // Prevents text selection or other default actions
        isDragging = true;
        let prevX = e.clientX;

        const onMouseMove = function (e) {
            if (!isDragging) return;

            const rect = container[0].getBoundingClientRect();
            const deltaX = e.clientX - prevX;
            prevX = e.clientX;

            const leftWidth = leftPane.offsetWidth + deltaX;
            const rightWidth = rightPane.offsetWidth - deltaX;

            // Set new widths as a percentage of the container width to maintain flexibility
            leftPane.style.width = `${(leftWidth / rect.width) * 100}%`;
            rightPane.style.width = `${(rightWidth / rect.width) * 100}%`;
        };

        const onMouseUp = function () {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
            isDragging = false;
        };

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });


    editor.Commands.add("open-code-editor", {
        run: (editor, sender) => {

            let htmlViewer = htmlCodeViewer.editor;
            let cssViewer = cssCodeViewer.editor;

            if (!htmlViewer) {

                htmlCodeViewer.init(container.find('.code-wraped>textarea')[0]);
                htmlViewer = htmlCodeViewer.editor;
            }
            if (!cssViewer) {
                cssCodeViewer.init(container.find('.code-wraped>textarea')[1]);
                cssViewer = cssCodeViewer.editor;
            }


            let InnerHtml = editor.getHtml();
            let Css = editor.getCss();

            htmlCodeViewer.setContent(InnerHtml);
            cssCodeViewer.setContent(Css);

            htmlViewer.refresh();
            cssViewer.refresh();

            console.log(htmlViewer, cssViewer);

            htmlViewer.on('change', function () {
                editor.setComponents(htmlViewer.getValue().trim());
                // editor.CssComposer.clear();
            })
            cssViewer.on('change', function () {
                editor.setStyle(cssViewer.getValue().trim());

            })

            editor.Modal.open({
                title: 'Code Editor',
                content: container,
                attributes: {
                    class: 'code-edit-modal',
                },
            });


            // Function to merge duplicate CSS selectors
            function mergeDuplicateSelectors(cssString) {
                // Parse CSS string into a structure
                const cssRules = {};
                cssString.split('}').forEach(rule => {
                    const selector = rule.split('{')[0].trim();
                    const declarations = rule.split('{')[1];
                    if (selector && declarations) {
                        if (!cssRules[selector]) {
                            cssRules[selector] = [];
                        }
                        cssRules[selector].push(declarations);
                    }
                });

                // Reconstruct CSS string with merged selectors
                let mergedCSS = '';
                for (const selector in cssRules) {
                    if (cssRules.hasOwnProperty(selector)) {
                        const mergedDeclarations = cssRules[selector].join(';');
                        mergedCSS += `${selector} {${mergedDeclarations}}`;
                    }
                }

                return mergedCSS;
            }
        }
    })


    // const codeMirror = editor.CodeManager.defViewers.CodeMirror;
    // codeMirror.attributes.readOnly = false;

    // codeMirror.on("change", function (e) {
    //     console.log(e);
    // });
    // console.log(codeMirror);
}