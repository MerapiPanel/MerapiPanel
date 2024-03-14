import React, { useState, useEffect } from 'react';
import { Editor, EditorConfig } from 'grapesjs';
import { EditorProps } from './panels/EditorPanel';



interface LayoutPanelTopProps {
    editor: Editor | null
}




const LayoutPanelTop = ({ editor }: LayoutPanelTopProps) => {



    useEffect(() => {

        if (editor === null) return;


    });

    return (
        <div className="layout__panel-top">
            <div className="panel__actions"></div>
            <div className="panel__devices"></div>
            <div className="panel__switcher"></div>
        </div>
    );
}

export default LayoutPanelTop;