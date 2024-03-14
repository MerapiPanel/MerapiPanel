import React, { useState, useEffect } from 'react';
import 'grapesjs/dist/css/grapes.min.css';
import '../style.scss';
import grapesjs, { EditorConfig } from 'grapesjs';
import { EditorProps, EditorState } from '../_define';



const Options: EditorConfig = {
    fromElement: true,
    height: '100%',
    storageManager: false,
    deviceManager: {
        devices: [{
            name: 'Desktop',
            width: '1280px',
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




const EditorPanel = ({ onReady }: EditorProps) => {

    const [editor, setEditor] = useState<EditorState>(null);
    const [delay, setDelay] = useState(10);


    useEffect(() => {

        if (editor !== null) return;

        if (delay > 0) {
            console.log(delay);
            setDelay(delay - 1);
            return;
        }

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

        initializedEditor.setComponents('<h1>Hello World</h1><p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p><div><img src="/merapi/base/assets/img/image-damaged.png" width="400" height="400" alt=""></div>');

    }, [delay, editor]);



    return (
        <div className="layout__panel-editor" id="editor"></div>
    )
}


export default EditorPanel;
export {
    setOptions,
    getOptions
}