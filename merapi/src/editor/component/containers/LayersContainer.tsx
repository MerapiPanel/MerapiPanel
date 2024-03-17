import React, { useEffect } from "react";
import { ContainerProps } from "../../Container";
import { useRoot } from "../../RootEditor";
import "./LayersContainer.scss";

const LayersContainer = (props: ContainerProps) => {

    const { config } = useRoot();

    config.layerManager = {
        appendTo: '.container-layers',
    }

    return (
        <div className="container-layers hide" id={props.id} ></div>
    )
}

export default LayersContainer;