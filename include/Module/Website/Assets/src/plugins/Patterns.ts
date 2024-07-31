import { Editor, Pages } from "grapesjs";
import { EditorView, basicSetup, minimalSetup } from 'codemirror';
import { autocompletion } from "@codemirror/autocomplete";
import { html } from '@codemirror/lang-html';
import { __MP } from "../../../../../Buildin/src/main";


const __: __MP = (window as any).__;

type Pattern = {
    id: any,
    name: string,
    components: any[],
    isChanged: boolean
}


export const Register = (editor: Editor, opts: {
    appendTo?: string
} = {}) => {


    const blockManager = editor.BlockManager;
    var newBlocksEl: any;


    editor.Components.addType("pattern", {
        model: {
            defaults: {
                droppable: false,
                editable: false,
                copyable: false,
                moveable: false,
                components: [],
                traits: [{
                    type: 'button',
                    text: 'Reset',
                    full: true, // Full width button
                    command: editor => { alert('Hello') },
                }],
                toolbar: [{
                    attributes: { class: 'fa fa-link-slash' },
                    command: 'tlb-move',
                }, {
                    attributes: { class: 'fa fa-clone' },
                    command: 'tlb-clone',
                }],
            }
        }
    });


    __.website.pattern.listpops((patterns: any[]) => {
        newBlocksEl = blockManager.render((patterns as any[]).map(pattern => {
            pattern.content = {
                type: "pattern",
                components: [pattern.content],
                attributes: {
                    "pattern-name": pattern.name
                }
            }
            return pattern;
        }), { external: true });
        render();
    });


    editor.Commands.add("pattern:customize", {

        run: function (editor, sender, opts) {
            console.log(opts);
            editor.setComponents(opts.content);
            adjustCanvas();
        }
    });

    // Function to adjust canvas size based on content
    function adjustCanvas() {
        const wrapper: any = editor.getWrapper();
        wrapper.addStyle({
            'min-height': '100vh',
            'display': 'flex',
            'flex-direction': 'column',
            'align-content': 'center',
            'justify-content': 'center'
        });
    }

    function render() {

        if (opts.appendTo) {
            const container = $(opts.appendTo);

            container.html("");
            container.append(
                $(`<div class="d-flex justify-content-between w-100">`)
                    .append(
                        $(`<h5>Patterns</h5>`),
                        $(`<button type="button" class="btn btn-sm text-primary border-primary rounded-0 border py-0 m-1"><i class="fa-solid fa-gears"></i></button>`)
                            .on('click', () => { })
                    ),
                $(`<div class="flex-grow-1">`)
                    .append(
                        newBlocksEl as any
                    )
            );
        }

    }

    render();
}