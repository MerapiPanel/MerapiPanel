import React from 'react';
import EditorPanel, { EditorProps } from './panels/EditorPanel';
import { Editor } from 'grapesjs';




const LayoutEditorRow = ({ onReady }: EditorProps) => {

    
    function handleOnReady(editor: Editor) {
        onReady(editor)
    }


    return (
        <div className="layout__panel-row">
            <EditorPanel onReady={handleOnReady} />
        </div>
    );
};

export default LayoutEditorRow;