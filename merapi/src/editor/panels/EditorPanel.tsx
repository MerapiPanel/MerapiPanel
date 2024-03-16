import React, { useState, useEffect } from 'react';
import 'grapesjs/dist/css/grapes.min.css';
import '../style.scss';
import grapesjs, { Editor, EditorConfig } from 'grapesjs';
import { EditorProps, EditorState } from '../_define';



const Options: EditorConfig = {
    fromElement: true,
    height: '100%',
    storageManager: false,
    deviceManager: {
        devices: [{
            name: 'Desktop',
            width: '',
        }, {
            name: 'Tablet',
            width: '768px',
            widthMedia: '1024px',
        }, {
            name: 'Mobile',
            width: '320px',
            widthMedia: '480px',
        }]
    },
    panels: { defaults: [] },
};


const setOptions = (options: Partial<EditorConfig>) => {
    Object.assign(Options, options);
}

const getOptions = () => Options;



// Function to select the body block
function selectBodyBlock(editor: Editor) {
    editor.select(editor.DomComponents.getWrapper());
}


const EditorPanel = ({ onReady }: EditorProps) => {

    const [editor, setEditor] = useState<EditorState>(null);
    const [count, setCount] = useState(5);


    useEffect(() => {

        if (count > 0) {
            setTimeout(() => {
                console.log(count);
                setCount(count - 1);
            }, 200);

            return;
        }

        if (editor !== null) return;

        Object.assign(Options, {
            container: '#editor',
        });

        const initializedEditor = grapesjs.init(Options);

        setEditor(initializedEditor);
        onReady(initializedEditor);


        initializedEditor.Commands.add('set-device-desktop', {
            run: editor => editor.setDevice('Desktop')
        });
        initializedEditor.Commands.add('set-device-tablet', {
            run: editor => editor.setDevice('Tablet')
        })
        initializedEditor.Commands.add('set-device-mobile', {
            run: editor => editor.setDevice('Mobile')
        });

        initializedEditor.setComponents('<h1 class="block__title">Hello World</h1><p class="block__content">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p><div class="block__image-container"><img class="block__image w-full" src="/merapi/base/assets/img/image-damaged.png" width="400" height="400" alt=""></div>');

        initializedEditor.on("load", () => {
            selectBodyBlock(initializedEditor);
        })

    }, [count, editor]);



    return (
        <div className="layout__panel-editor" id="editor"></div>
    )
}


export default EditorPanel;
export {
    setOptions,
    getOptions
}