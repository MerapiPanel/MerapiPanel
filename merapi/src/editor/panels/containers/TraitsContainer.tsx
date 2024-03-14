import React from "react";
import { PanelProps } from "../../_define";
import { setOptions } from "../EditorPanel";


const TraitsContainer = ({ editor }: PanelProps) => {

    setOptions({
        traitManager: {
            appendTo: '.traits-container',
        }
    })

    return (
        <div className="traits-container" style={{ display: 'none' }}></div>
    )
}

export default TraitsContainer;