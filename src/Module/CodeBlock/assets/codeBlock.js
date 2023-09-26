
import $ from 'jquery';

const CodeBlock = (editor) => {


    editor.TraitManager.addType('custom-code', {
        createInput({ trait }) {

            const icon = `<svg style="transform: rotateY(180deg); fill: currentColor;" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>`;

            this.codeText = "";
            this.handle = $(`<button>${icon}</button>`);
            this.handle.css({
                padding: "1rem",
                with: "100%",
                height: "100%",
            });
            return this.handle.get(0);
        },

        onEvent({ component }) {
            const value = this.codeText || null;
            component.addAttributes({ value });
        },

        onUpdate({ component }) {
            const value = component.getAttributes().value || 0;
            this.codeText = value;
        },
    });


    editor.DomComponents.addType('custom-code', {

        // Make the editor understand when to bind `custom-code-type`
        isComponent: function (el) {
            return el.tagName === 'CUSTOMCODE';
        },

        // Model definition
        model: {
            // Default properties
            defaults: {
                CodeBlock: 'code',
                tagName: 'customcode',
                droppable: false, // Can't drop other elements inside
                traits: [{
                    type: "checkbox",
                    name: "show-output",
                    label: "Show Output",

                }, {
                    type: "custom-code",
                    name: "CodeBlock",
                }],

            },

            init() {
                // Here we can listen global hooks with editor.on('...')
                editor.on('component:update:CodeBlock', this.handleValueChange);
            },
            updated(property, value, prevValue) {

            },
            removed() {
            },
            handleValueChange() {
                console.log('handleValueChange');
            },

        },

        view: {
        },
    });


    const bm = editor.BlockManager;

    bm.add('custom-code', {
        label: 'Custom Code',
        content: {
            type: 'custom-code',
            content: 'Hello, world!',
            style: { padding: '10px', backgroud: 'black' },
        },
        category: 'Basic',
        attributes: {
            customCode: 'Insert h1 block',

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