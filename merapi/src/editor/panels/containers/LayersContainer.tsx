import React, { useEffect } from "react";
import { PanelProps } from "../../_define";
import { Options, useOptions } from "../../provider/Options";

const LayersContainer = ({ editor }: PanelProps) => {

    const { setOptions } = useOptions();

    setOptions({
        editorOptions: {
            layerManager: {
                appendTo: '.container-layers',
            }
        }
    });


    return (
        <div className="container-layers"></div>
    )
}

export default LayersContainer;