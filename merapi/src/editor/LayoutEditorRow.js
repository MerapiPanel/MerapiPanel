import React from 'react';
import EditorPanel from './panels/EditorPanel';

const LayoutEditorRow = ({ onReady, options }) => {

    function handleOnReady(editor) {
        onReady(editor)
    }


    return (
        <div className="layout__panel-row">
            <EditorPanel onReady={handleOnReady} options={options} />
        </div>
    );
};

export default LayoutEditorRow;