import React, { useEffect } from 'react';
import { ContainerProps } from '../../Container';
import { useRoot } from '../../RootEditor';
import "./StyleContainer.scss";

const StylesContainer: React.FC<ContainerProps> = (props: ContainerProps) => {

    const { config } = useRoot();

    config.styleManager = {
        appendTo: '.container-styles',
    }
    config.selectorManager = {
        appendTo: '.container-styles'
    }

    config.colorPicker = {
        appendTo: 'parent',
        containerClassName: 'color-picker',
        togglePaletteOnly: false,
        offset: { top: 26, left: -180, },
    }

    return (
        <div className='container-styles' id={props.id} ></div>
    )
}


export default StylesContainer;