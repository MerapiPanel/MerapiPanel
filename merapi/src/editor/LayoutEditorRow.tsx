import React, { useState } from 'react';
import EditorPanel from './panels/EditorPanel';
import { Editor } from 'grapesjs';
import { EditorProps, EditorState } from './_define';
import LeftPanel from './panels/LeftPanel';
import RightPanel from './panels/RightPanel';




const LayoutEditorRow = ({ onReady }: EditorProps) => {

    const [editor, setEditor] = useState<EditorState>(null);

    function handleOnReady(editor: Editor) {
        onReady(editor);
        setEditor(editor);
    }


    return (
        <div className="layout__panel-row">
            <LeftPanel  editor={editor}/>
            <EditorPanel onReady={handleOnReady} />
            <RightPanel editor={editor} />
        </div>
    );
};

export default LayoutEditorRow;