import React, { useEffect } from 'react';
import { PanelProps } from '../../_define';
import { useOptions } from '../../provider/Options';

const StylesContainer = ({ editor }: PanelProps) => {

    const { setOptions } = useOptions();

    setOptions({
        editorOptions: {
            selectorManager: {
                appendTo: '.container-styles'
            },
            styleManager: {
                appendTo: '.container-styles',
            },
            colorPicker: {
                appendTo: 'parent',
                containerClassName: 'color-picker',
                togglePaletteOnly: false,
                offset: { top: 26, left: -180, },
            },
        }
    });


    return (
        <div className='container-styles'></div>
    )
}


export default StylesContainer;