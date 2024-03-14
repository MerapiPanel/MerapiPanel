import React, { useState, useEffect } from 'react';
import { Editor, EditorConfig } from 'grapesjs';
import LayoutPanelTop from './LayoutPanelTop';
import LayoutEditorRow from './LayoutEditorRow';
import { EditorState } from './_define';


const LayoutMain = () => {

    const [editor, setEditor] = useState<EditorState>(null);

    function handleOnReady(editor: Editor) {
        setEditor(editor);
    }


    return (
        <>
            <LayoutPanelTop editor={editor} />
            <LayoutEditorRow onReady={handleOnReady} />
        </>
    )
}

export default LayoutMain;
