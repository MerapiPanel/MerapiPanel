import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Checklist from '@editorjs/checklist';
import Embed from '@editorjs/embed';
import Quote from '@editorjs/quote';
import NestedList from '@editorjs/nested-list';
import TextVariantTune from '@editorjs/text-variant-tune';
import Table from '@editorjs/table'


import Paragraph from './tools/Paragraph';


function init(args = {}) {


    Object.assign({
        editor: {
            holder: null
        }
    }, args);

    if (!args.editor.holder) {
        merapi.toast('Editor holder is not defined', 5, 'text-warning');
        return;
    }

    if (typeof args.editor.holder == "string") {

        args.editor.holder = String(args.editor.holder).charAt(0) == "#" ? args.editor.holder.slice(1) : args.editor.holder;
    }


    const editor = new EditorJS({
        placeholder: 'Let`s write an awesome story!',
        holder: args.editor.holder,
        autofocus: true,
        tools: {
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
                tunes: ['textVariant']
            },
            header: {
                class: Header,
            },
            list: {
                class: NestedList,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'ordered'
                },
            },
            table: {
                class: Table,
                inlineToolbar: true,
                config: {
                    rows: 2,
                    cols: 3,
                },
            },
            checklist: {
                class: Checklist
            },
            quote: {
                class: Quote
            },
            textVariant: TextVariantTune,
            embed: {
                class: Embed,
                config: {
                    services: {
                        youtube: true,
                        coub: true
                    }
                }
            },
            
        },
    });


}

merapi.assign("editor", {
    init
});