import React, { useState, useEffect } from 'react';
import 'grapesjs/dist/css/grapes.min.css';
import grapesjs from 'grapesjs';

const EditorPanel = ({ onReady, options }) => {

    const [editor, setEditor] = useState(null);



    useEffect(() => {
        if (editor !== null) return;
        setTimeout(() => {

            Object.assign(options, { container: '#editor' });
            console.log(options);

            const initializedEditor = grapesjs.init(options);

            setEditor(initializedEditor as any);
            onReady(initializedEditor);
        }, 1000);
        
    }, [editor]);



    return (
        <div className="layout__panel-editor" id="editor"></div>
    )
}

export default EditorPanel;