import React, { useEffect } from "react";
import { usePanel } from "./Panel";
import { useRoot } from "../RootEditor";
import { Command, CommandObject, Editor } from "grapesjs";


type ButtonProps = {
    id: string,
    children: React.ReactElement | string
    className?: string,
    command: string | CommandObject,
    attributes?: {},
    active?: boolean,
    togglable?: boolean,
    context?: string,
}



const Button = (props: ButtonProps) => {

    return (<></>);
};


export default Button;
export type { ButtonProps };