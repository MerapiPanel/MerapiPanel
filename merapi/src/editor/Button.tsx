import React, { useEffect } from "react";
import { usePanel } from "./component/Panel";
import { useRoot } from "./RootEditor";
import { Editor } from "grapesjs";


type ButtonProps = {
    id: string,
    label: string|JSX.Element,
    className?: string,
    command: string,
    attributes?: {},
    active?: boolean,
}


const registerButton = (editor: Editor, panel_id: string, props: ButtonProps) => {

    const btn = editor.Panels.addButton(panel_id, {
        id: props.id,
        className: props.className,
        label: props.label,
        command: props.command,
        attributes: props.attributes,
        active: props.active,
    });

    console.log(btn);
}


const Button = (props: ButtonProps) => {

    const { id } = usePanel();
    const { editor } = useRoot();

    useEffect(() => {
        console.log("Register button", id);
        // if (editor === null) return;
        // registerButton(editor, id, props);
    }, [id]);

    return (<></>);
};


export default Button;
export type { ButtonProps };