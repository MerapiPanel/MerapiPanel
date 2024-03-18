import React from "react";

interface ContainerProps {
    children?: React.ReactNode
    id?: string,
    style?: React.CSSProperties
}

const Container = (props: ContainerProps) => {
    return (
        <div id={props.id} >
            {props.children}
        </div>
    )
}

export default Container;
export type { ContainerProps }