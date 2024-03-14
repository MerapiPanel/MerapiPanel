import React, { useEffect } from "react";
import { Editor } from "grapesjs";
import { PanelProps } from "../_define";
import { setOptions } from "./EditorPanel";


const LeftPanel = ({ editor }: PanelProps) => {

    setOptions({
        layerManager: {
            appendTo: '.layers-container',
        }
    })
    

    useEffect(() => {
        if (editor === null) return;

        editor.Panels.addPanel({
            id: 'panel-left',
            el: '.layout__panel-left',
        });
    });


    return (
        <div className="layout__panel-left">
            <div className="layers-container"></div>
        </div>
    )
}


export default LeftPanel;