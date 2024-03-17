import React from "react";

interface ContainerProps {
    children?: React.ReactElement<any>[]
}

const Container = (props: ContainerProps) => {
    return (
        <>
            {props.children}
        </>
    )
}

export default Container;
export type { ContainerProps }