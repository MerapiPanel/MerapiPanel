import React, { useState, useEffect } from 'react';
import 'grapesjs/dist/css/grapes.min.css';
import '../style.scss';
import grapesjs, { Editor } from 'grapesjs';



const Options = {
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




type onReady = (editor: Editor) => void



interface EditorProps {
    onReady: onReady
}



// Define the type for the state variable
type EditorState = Editor | null;




const EditorPanel = ({ onReady }: EditorProps) => {

    const [editor, setEditor] = useState<EditorState>(null);



    useEffect(() => {

        if (editor !== null) return;

        Object.assign(Options, {
            container: '#editor',
        });

        const initializedEditor = grapesjs.init(Options);

        setEditor(initializedEditor);
        onReady(initializedEditor);

    }, [editor]);



    return (
        <div className="layout__panel-editor" id="editor"></div>
    )
}




export default EditorPanel;
export type { onReady, EditorProps, EditorState };