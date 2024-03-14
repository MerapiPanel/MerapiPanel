import React, { useState, StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import LayoutPanelTop from './editor/LayoutPanelTop';
import LayoutEditorRow from './editor/LayoutEditorRow';
import { Editor } from 'grapesjs';
import { EditorState } from './editor/panels/EditorPanel';


const MP_Editor = () => {

    const [editor, setEditor] = useState<EditorState>(null);

    function handleOnReady(editor: Editor) {
        setEditor(editor);
    }

    return (
        <>
            <div className="container__editor">
                <LayoutPanelTop editor={editor} />
                <LayoutEditorRow onReady={handleOnReady} />
            </div>
        </>
    );
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_Editor />
    </StrictMode>
)