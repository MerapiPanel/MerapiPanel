import React from "react";
import { PanelProps } from "../../_define";
import { setOptions } from "../EditorPanel";


const TraitsContainer = ({ editor }: PanelProps) => {

    setOptions({
        traitManager: {
            appendTo: '.container-traits',
        }
    })

    return (
        <div className="container-traits" style={{ display: 'none' }}></div>
    )
}

export default TraitsContainer;