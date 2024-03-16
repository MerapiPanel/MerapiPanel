import React, { useEffect } from "react";
import { PanelProps } from "../../_define";
import { useOptions } from "../../provider/Options";


const TraitsContainer = ({ editor }: PanelProps) => {

    const { setOptions } = useOptions();

    setOptions({
        editorOptions: {
            traitManager: {
                appendTo: '.container-traits',
            }
        }
    });

    return (
        <div className="container-traits" style={{ display: 'none' }}></div>
    )
}

export default TraitsContainer;