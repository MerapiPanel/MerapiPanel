import React from "react";
import { ContainerProps } from "../Container";

interface Props {
    children?: React.ReactElement<ContainerProps>
}

const Panel = (props: Props) => {
    return (
        <div className="editor__panel">
            {props.children}
        </div>
    )
}

export default Panel;