import React from "react";
import { PanelProps } from "../../_define";
import { setOptions } from "../EditorPanel";

const LayersContainer = ({ editor }: PanelProps) => {

    setOptions({
        layerManager: {
            appendTo: '.container-layers',
        }
    });

    return (
        <div className="container-layers"></div>
    )
}

export default LayersContainer;