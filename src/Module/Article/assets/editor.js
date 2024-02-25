import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Checklist from '@editorjs/checklist';
import Embed from '@editorjs/embed';
import Quote from '@editorjs/quote';
import NestedList from '@editorjs/nested-list';
import Table from '@editorjs/table'

import "./style/article.scss";


import Paragraph from './tools/Paragraph';


function init(args = {}) {


    Object.assign({
        editor: {
            holder: null
        },
        event: {
            onReady: null,
            onChange: null
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
        data: {
            blocks: [
                {
                    type: 'header',
                    data: {
                        text: 'Hallo World',
                        level: 1,
                    }
                },
                {
                    type: 'header',
                    data: {
                        text: 'Hallo Dunia',
                        level: 2
                    }
                },
                {
                    type: 'header',
                    data: {
                        text: 'Hallo World is a better than any thing what you want for example is anonimouse is also type hallo world in each they do coding on their desktop',
                        level: 1,
                    }
                },
                {
                    type: 'header',
                    data: {
                        text: 'Yanto odading',
                        level: 3
                    }
                }
            ]
        },
        tools: {
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
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
        onReady: () => {
            if (typeof args.event?.onReady == "function") args.event?.onReady()
        },
        onChange: (api, evt) => {
            if (typeof args.event?.onChange == "function") args.event?.onChange(api, evt)
        }
    });


    return editor;
}

merapi.assign("editor", {
    init
});