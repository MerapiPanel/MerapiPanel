import React, { useEffect } from "react";
import { ContainerProps } from "../../Container";
import { useRoot } from "../../RootEditor";



const TraitsContainer = (props: ContainerProps) => {

    const { config } = useRoot();

    config.traitManager = {
        appendTo: '.container-traits',
    }

    return (
        <div className="container-traits" id={props.id}></div>
    )
}

export default TraitsContainer;