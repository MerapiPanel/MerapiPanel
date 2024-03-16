import { Device as _Device, EditorConfig } from "grapesjs";
import React, { useState, createContext, useContext, useEffect } from "react";


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


function isObject(item) {
    return (item && typeof item === 'object' && !Array.isArray(item));
}


interface Device extends _Device {
    icon?: string
}

type Options = {
    progress?: number
    editorOptions?: EditorConfig
}

interface OptionsType {
    progress: number
    editorOptions: EditorConfig
    setOptions: (options: Options) => void
}

const OptionsContext = createContext({} as OptionsType);
export const useOptions = () => useContext(OptionsContext);





const initialOptions: Options = {
    progress: 0,
    editorOptions: {
        fromElement: true,
        height: '100%',
        storageManager: false,
        deviceManager: {
            devices: [{
                name: 'Desktop',
                width: '',
                icon: 'fa-desktop',
            }, {
                name: 'Tablet',
                width: '768px',
                widthMedia: '1024px',
                icon: 'fa-tablet',
            }, {
                name: 'Mobile',
                width: '320px',
                widthMedia: '480px',
                icon: 'fa-mobile',
            }] as any
        },
        panels: { defaults: [] },
        cssIcons: undefined,
    }
}




export const OptionsProvider = ({ children }) => {

    const [options, setOptions] = useState(initialOptions as any);

    const _console = {
        error: console.error,
        warn: console.warn
    }

    console.error = function () { };
    console.warn = function () { };

    const setOptionsHandler = (_options: Options) => {
        setOptions((prevOptions: any) => DeepMerge(prevOptions, _options));

    }



    useEffect(() => {
        setTimeout(() => {
            console.error = _console.error;
            console.warn = _console.warn;
        }, 100);
    }, []);


    return (
        <OptionsContext.Provider value={{ ...options, setOptions: setOptionsHandler }}>
            {children}
        </OptionsContext.Provider>
    );
}

export {
    DeepMerge
}
export type {
    Options,
    Device
}