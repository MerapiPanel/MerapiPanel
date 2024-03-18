import React, { createContext, useContext, useEffect, useRef, useState } from "react";
import grapesjs, { Editor, EditorConfig } from "grapesjs";


function isObject(item) {
    return (item && typeof item === 'object' && !Array.isArray(item));
}



const DeepMerge = (target: any, ...sources: any) => {

    let target_clone = { ...target };

    sources.forEach((source: { [x: string]: any; }) => {
        // Iterate through `source` properties and if an `Object` set property to merge of `target` and `source` properties
        for (const key in source) {

            if (isObject(source[key])) {
                if (!(key in target_clone)) {
                    target_clone[key] = {};
                }
                target_clone[key] = DeepMerge(target_clone[key], source[key]);
            }
            else if (Array.isArray(source[key])) {

                if (key in target_clone) {
                    for (const i in source[key]) {

                        if (typeof source[key][i] === "object") {

                            let isDuplicate = false;

                            for (const j in target_clone[key]) {

                                let sortedTarget = Object.keys(target_clone[key][j]).sort().reduce((acc, x) => {
                                    acc[x] = target_clone[key][j][x];
                                    return acc;
                                }, {});
                                let sortedSource = Object.keys(source[key][i]).sort().reduce((acc, x) => {
                                    acc[x] = source[key][i][x];
                                    return acc;
                                }, {});


                                let fromTarget = JSON.stringify(sortedTarget).toLowerCase();
                                let fromSource = JSON.stringify(sortedSource).toLowerCase();
                                if (fromSource === fromTarget) {
                                    isDuplicate = true;
                                    break;
                                }
                            }

                            if (!isDuplicate) {
                                (target_clone[key] as Array<any>).push(source[key][i]);
                            }
                        }
                        else if ((target_clone[key] as Array<any>).indexOf(source[key][i]) === -1) {
                            (target_clone[key] as Array<any>).push(source[key][i]);
                        }
                    }
                } else {
                    target_clone[key] = source[key];
                }
            }

            else {
                target_clone[key] = source[key];
            }
        }
    })

    return target_clone;
}



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
    progress: number,
    setProgress: React.Dispatch<React.SetStateAction<number>>,
    canvasRef: React.RefObject<HTMLDivElement>,
    editor: Editor | null,
    setEditor: React.Dispatch<React.SetStateAction<Editor | null>>,
    config: EditorConfig
}
const RootContext = createContext({} as IRoot);

export const useRoot = () => useContext(RootContext);







interface RootProps {
    children?: React.ReactElement[],
    onReady?: (editor: Editor) => void,
    config?: EditorConfig
}


const RootEditor = (props: RootProps) => {

    const canvasRef = useRef<HTMLDivElement | null>(null);
    const [editor, setEditor] = useState<Editor | null>(null);
    const [count, setCount] = useState<number>(5);
    const [progress, setProgress] = useState<number>(0);

    const initial: IRoot = {
        progress,
        setProgress,
        canvasRef: canvasRef,
        editor,
        setEditor,
        config: {
            fromElement: true,
            height: '100%',
            width: "100%",
            storageManager: false,
            layerManager: {
                stylePrefix: "merapi__editor-"
            },
            stylePrefix: "merapi__editor-",
            cssIcons: undefined,
            colorPicker: {
                containerClassName: 'color-picker',
            },
            // Avoid any default panel
            panels: { defaults: [] },
        }
    };


    fetch('/merapi/config/app.yml')
        .then(response => response.json())
        .then(data => {
            initial.config = DeepMerge(initial.config, data);
        });


    useEffect(() => {
        if (props.config) {
            initial.config = DeepMerge(initial.config, props.config);
        }
    }, [props.config]);



    useEffect(() => {

        if (count > 0) {
            setTimeout(() => { setCount(count - 1); setProgress(40 - (count * 100) / 20); }, 200);

            return;
        }
        if (canvasRef.current === null) {
            console.error("cant find canvas component");
            return;
        }

        fetch('/merapi/config/app.yml')

        initial.config.container = canvasRef.current as HTMLElement;

        const initialEditor = grapesjs.init(initial.config);
        setEditor(initialEditor as any);
        initialEditor.onReady(() => {
            if (props.onReady) {
                props.onReady(initialEditor);
            }
        });

        setProgress(100);

    }, [count, canvasRef]);

    return (
        <RootContext.Provider value={initial}>
            <div className="merapi__editor">
                {props.children}
            </div>
        </RootContext.Provider>
    )
}

export default RootEditor;