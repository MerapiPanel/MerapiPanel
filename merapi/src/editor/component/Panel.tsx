import React, { createContext, useContext, useEffect } from "react";
import { ContainerProps } from "../Container";
import { useRoot } from "../RootEditor";
import { Editor } from "grapesjs";
import { ButtonProps } from "../Button";
import { renderToStaticMarkup } from 'react-dom/server'


interface PanelProps {
    children?: React.ReactElement<ContainerProps | ButtonProps> | React.ReactElement<ContainerProps | ButtonProps>[]
    id: string,
    className?: string
}

const PanelContext = createContext({} as PanelProps);

export const usePanel = () => useContext(PanelContext);



const findButtonTypeInChildren = (children: any[], callback: Function) => {

    React.Children.forEach(children, child => {
        if (React.isValidElement(child)) {
            // Check if the child is the target type
            if (child.type === "Button" || (child.type && (child.type as any).name === "Button")) {
                callback(child);
            }

            // If the child has its own children, recurse
            if (child.props && (child.props as any).children) {
                findButtonTypeInChildren((child.props as any).children, callback);
            }
        } else if (Array.isArray(child)) {
            // If the child is an array, recurse through its elements
            findButtonTypeInChildren(child, callback);
        }
    });
};

const registerPanel = (editor: Editor, id: string, btnProps: ButtonProps[]) => {

    console.log("UPDATE UI", id);

    editor.Panels.addPanel({
        id,
        el: `#${id}`,
        buttons: btnProps.map((btn) => {
            return {
                id: btn.id,
                className: btn.className,
                label: typeof btn.label === 'string' ? btn.label : renderToStaticMarkup(btn.label),
                command: btn.command,
                attributes: btn.attributes,
                active: btn.active,
            }
        }),
    });

}

const Panel = (props: PanelProps) => {
    const { editor } = useRoot();
    const [mookProps, setMookProps] = React.useState<PanelProps>(props);

    useEffect(() => {
        if (editor == null) return;

        // find buttonType in children
        const buttonsProps: ButtonProps[] = [];
        findButtonTypeInChildren(props.children as any, (btn: any) => {
            buttonsProps.push(btn.props); // push the button props
        });
        // register the panel with buttons props
        registerPanel(editor, props.id, buttonsProps);


        setTimeout(() => {
            setMookProps(props); // toggle change for delay
        }, 100);
    }, [editor]);

    return (
        <PanelContext.Provider value={mookProps}>
            <div className={"merapi__editor--panel " + (props.className ? props.className : "")} id={props.id}>
                {props.children}
            </div>
        </PanelContext.Provider>
    )
}

export default Panel;
export type { PanelProps }