import React, { useState, StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import LayoutPanelTop from './editor/LayoutPanelTop';
import LayoutEditorRow from './editor/LayoutEditorRow';
import "./editor/style.scss";
import { EditorConfig } from 'grapesjs';


const options: EditorConfig = {
    fromElement: true,
    height: '100%',
    storageManager: false,
    deviceManager: {
        devices: [{
            name: 'Desktop',
            width: '1280px', // default size
        }, {
            name: 'Tablet',
            width: '768px', // this value will be used on canvas width
            widthMedia: '1024px', // this value will be used in CSS @media
        }, {
            name: 'Mobile',
            width: '320px', // this value will be used on canvas width
            widthMedia: '480px', // this value will be used in CSS @media
        }]
    },
    panels: {
        defaults: []
    }
}



const MP_Editor = () => {

    const [editor, setEditor] = useState(null);

    function handleOnReady(editor) {
        setEditor(editor);
    }

    return (
        <>
            <div className="container__editor">
                <LayoutPanelTop editor={editor} options={options} />
                <LayoutEditorRow onReady={handleOnReady} options={options} />
            </div>
        </>
    );
};



const root = createRoot(document.getElementById('root'));

root.render(
    <StrictMode>
        <MP_Editor />
    </StrictMode>
)