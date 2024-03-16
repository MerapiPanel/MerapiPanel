import React, { createContext, useContext, useEffect, useRef, useState } from "react";
import { CanvasProps } from "./Canvas";
import grapesjs, { Editor } from "grapesjs";


const findCanvasTypeInChildren = (children, callback) => {
    React.Children.forEach(children, child => {
        if (React.isValidElement(child)) {
            // Check if the child is the target type
            if (child.type === "Canvas" || (child.type && (child.type as any).name === "Canvas")) {
                callback(child);
            }

            // If the child has its own children, recurse
            if (child.props && (child.props as any).children) {
                findCanvasTypeInChildren((child.props as any).children, callback);
            }
        } else if (Array.isArray(child)) {
            // If the child is an array, recurse through its elements
            findCanvasTypeInChildren(child, callback);
        }
    });
};

interface IRoot {
    canvasRef: React.RefObject<HTMLDivElement>;
}
const RootContext = createContext({} as IRoot);

export const useRoot = () => useContext(RootContext);







interface RootProps {
    children?: React.ReactElement[]
}


const RootEditor = (props: RootProps) => {

    const canvasRef = useRef<HTMLDivElement>(null);
    const [editor, setEditor] = useState(null);

    useEffect(() => {
        
        if(editor !== null) return;

        const initialEditor = grapesjs.init({
            container: canvasRef.current as HTMLElement,
            height: "100%",
            width: "100%",
            fromElement: true
        });

        setEditor(initialEditor as any);

    }, [canvasRef]);

    return (
        <RootContext.Provider value={{ canvasRef }}>
            {props.children}
        </RootContext.Provider>
    )
}

export default RootEditor;