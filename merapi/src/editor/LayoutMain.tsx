import React, { useState, useEffect } from 'react';
import { Editor, EditorConfig } from 'grapesjs';
import LayoutTop from './LayoutTop';
import LayoutRow from './LayoutRow';
import { EditorState } from './_define';


const LayoutMain = () => {

    const [editor, setEditor] = useState<EditorState>(null);

    function handleOnReady(editor: Editor) {
        setEditor(editor);
    }


    return (
        <>
            <LayoutTop editor={editor} />
            <LayoutRow onReady={handleOnReady} />
        </>
    )
}

export default LayoutMain;
