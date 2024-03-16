import React, { useEffect, useState } from "react";
import { PanelProps } from "../../_define";
import { isString } from "underscore";

const SelectedContainer = ({ editor }: PanelProps) => {

    const [name, setName] = useState("");
    const [icon, setIcon] = useState("");

    useEffect(() => {
        if (editor == null) return;

        editor.on('component:selected', (e) => {

            setName(e.get('type').length > 0 ? e.get('type') : e.get('tagName'));

            let _icon = e.get('icon');
            if(isString(_icon) && _icon.length <= 0) {
                _icon = e.get('fallback');
            }
            if(!_icon || (isString(_icon) && _icon.length <= 0)) {
                _icon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-slash-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M11.354 4.646a.5.5 0 0 0-.708 0l-6 6a.5.5 0 0 0 .708.708l6-6a.5.5 0 0 0 0-.708"/>
              </svg>`;
            }
            
            _icon = _icon.replace(/width=["'].*?["']/gi, "width=\"35\"")
            .replace(/height=["'].*?["']/gi, "height=\"35\"")
            .replace(/(scale|fill|style)=["'].*?["']/gi, "")
            .replace(/<svg(.*)>/gi, "<svg $1 fill=\"currentColor\">");

            setIcon(_icon);
        });
    }, [editor]);


    return (
        <div className="container-selected">
            <div className="component-icon" dangerouslySetInnerHTML={{ __html: icon }}></div>
            <div className="component-name">{name}</div>
        </div>
    );
};

export default SelectedContainer;