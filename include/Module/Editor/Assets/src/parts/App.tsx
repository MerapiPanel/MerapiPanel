import React, { createContext, useContext, useState } from "react";
import { Payload } from "../main";
import { App as EditorApp, Navbar, EditorBody, EditorCanvas, Sidebar, LoadingScreen, LoadingAction } from "@il4mb/merapipanel/editor";
import { Panel, Btn, LayerManager, BlockManager, StyleManager, TraitsManager, SelectorManager } from "@il4mb/merapipanel/editor/partial";
import { AddComponentTypeOptions, BlockProperties, Editor } from "grapesjs";
import { CodeEditor, Bootstrap5 } from "@il4mb/merapipanel/editor/plugins";
import $ from "jquery";
import { http, toast } from "@il4mb/merapipanel";


interface BlockDefine {
    extend: string
    name: string
    catergory: string
    label: string
    media: string
    index: string
    defaults: [string, string]
}

const createBlockConfig = (blockDefine: BlockDefine): BlockProperties => {

    return {
        label: blockDefine.label || blockDefine.name,
        media: blockDefine.media || '<i class="fa-regular fa-face-smile fa-3x"></i>',
        category: blockDefine.catergory || "Basic",
        content: {
            type: blockDefine.name
        }
    }
}


const ___InitRegisterBlocks = async (editor: Editor, blocks: any[], setLoadingWidth: React.Dispatch<React.SetStateAction<number>>) => {

    const { Components, BlockManager } = editor;
    const blockSize = blocks.length;
    let loaded = 0;

//    console.clear();

    for (const i in blocks) {

        const blockDefine: BlockDefine = blocks[i];

        (window as any).BlockRegister = (modelCallback: any) => {

            const regType: AddComponentTypeOptions = {
                extend: blockDefine.extend || "",
                model: {
                    defaults: {
                        components: {
                            tagName: "div",
                            components: blockDefine.name
                        },
                        ...blockDefine.defaults
                    },
                    ...modelCallback,
                    updated(prop, val, prev) {

                        if (prop === "components") {
                            this.view?.render();
                        }
                    }
                },
                view: {
                    render() {
                        const components = this.model.components();

                        if (!components || components.length <= 0) {
                            this.el.innerHTML = "";
                            this.el.appendChild($(`<div class='text-center py-3' style="opacity: 0.5; font-size: 0.7em">
                                <div>Drag components here</div>
                                <i>${this.model.get('type')}</i>
                            </div>`)[0]);
                        } else {
                            this.renderChildren();
                        }


                        return this;
                    }
                }
            };

            Components.addType(blockDefine.name, regType);
            BlockManager.add(blockDefine.name, createBlockConfig(blockDefine));
        }
        await import(/* webpackIgnore: true */ blockDefine.index);

        loaded++;
        let persentage = (loaded / blockSize) * 40;
        setLoadingWidth(__current => __current + persentage);
    }

    editor.load();
    setTimeout(() => setLoadingWidth(100), 1000);

}



export const App = ({ payload }: { payload: Payload }) => {

    const [loadingWidth, setLoadingWidth] = useState(10);
    const [loadinfError, setLoadingError] = useState(false);
    const [loadingActionShow, setLoadingActionShow] = useState(false);

    const devices = [
        {
            name: "Desktop",
            width: "",
        },
        {
            name: "Tablet",
            width: "768px",
            widthMedia: "1024px",
        },
        {
            name: "Mobile",
            width: "320px",
            widthMedia: "480px",
        },
    ];


    const onReadyHandler = (editor: Editor) => {

        setLoadingWidth(35);
        try {

            setTimeout(() => {
                if (typeof payload.onReady === "function") {
                    payload.onReady(editor);
                }
            }, 50)

            if (payload.fetchBlockURL) {
                fetch(payload.fetchBlockURL).then(res => res.json()).then(res => ___InitRegisterBlocks(editor, res.data, setLoadingWidth))
            } else {
                throw new Error('fetchBlockURL is not define');
            }

        } catch (error) {
            console.error(error);
            setLoadingError(true);
        }
    }

    const handleSave = (editor: Editor) => {
        setLoadingActionShow(true);


        const data = {
            components: JSON.parse(JSON.stringify(editor.getComponents())),
            css: editor.getCss(),
        }

        const binder: any = {
            callback: payload.callback
        }

        new Promise((resolve, reject) => {
            binder.reject = reject;
            binder.resolve = resolve;
            binder.editor = editor;
            binder.data = data;
            binder.callback(data);

        }).catch((error: any) => {

            toast(typeof error === "string" ? error : (error.message || error.statusText || "Error"), 5, 'text-danger');


        }).finally(() => {
            setLoadingActionShow(false);
        })
    }


    return (
        <EditorApp
            deviceManager={{ devices: devices }}
            plugins={payload.config.plugins || [CodeEditor, Bootstrap5, (editor) => editor.onReady(onReadyHandler)]}
            canvas={(payload.config.canvas || {}) as any}
            pluginsOpts={payload.config.pluginsOptions || {}}>
            <Navbar>
                <Panel id="sidebar-panel">
                    <Btn
                        id="layers-btn"
                        togglable={false}
                        context="sidebar"
                        command={{
                            run: () => {
                                document.querySelector(".editor-sidebar .layer-manager")?.classList.remove("hide");
                            },
                            stop: () => {
                                document.querySelector(".editor-sidebar .layer-manager")?.classList.add("hide");
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.5 11.5A.5.5 0 0 1 5 11h10a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 1 3h10a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5" />
                        </svg>
                    </Btn>
                    <Btn
                        id="styles-btn"
                        togglable={false}
                        context="sidebar"
                        command={{
                            run: () => {
                                document.querySelector(".editor-sidebar .style-manager")?.classList.remove("hide");
                            },
                            stop: () => {
                                document.querySelector(".editor-sidebar .style-manager")?.classList.add("hide");
                            }
                        }} active>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.825.12a.5.5 0 0 1 .132.584c-1.53 3.43-4.743 8.17-7.095 10.64a6.1 6.1 0 0 1-2.373 1.534c-.018.227-.06.538-.16.868-.201.659-.667 1.479-1.708 1.74a8.1 8.1 0 0 1-3.078.132 4 4 0 0 1-.562-.135 1.4 1.4 0 0 1-.466-.247.7.7 0 0 1-.204-.288.62.62 0 0 1 .004-.443c.095-.245.316-.38.461-.452.394-.197.625-.453.867-.826.095-.144.184-.297.287-.472l.117-.198c.151-.255.326-.54.546-.848.528-.739 1.201-.925 1.746-.896q.19.012.348.048c.062-.172.142-.38.238-.608.261-.619.658-1.419 1.187-2.069 2.176-2.67 6.18-6.206 9.117-8.104a.5.5 0 0 1 .596.04" />
                        </svg>
                    </Btn>
                    <Btn
                        id="blocks-btn"
                        togglable={false}
                        context="sidebar"
                        command={{
                            run: () => {
                                document.querySelector(".editor-sidebar .block-manager")?.classList.remove("hide");
                            },
                            stop: () => {
                                document.querySelector(".editor-sidebar .block-manager")?.classList.add("hide");
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                        </svg>
                    </Btn>
                    <Btn
                        id="traits-btn"
                        togglable={false}
                        context="sidebar"
                        command={{
                            run: () => {
                                document.querySelector(".editor-sidebar .traits-manager")?.classList.remove("hide");
                            }, stop: () => {
                                document.querySelector(".editor-sidebar .traits-manager")?.classList.add("hide");
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.5 1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4H1.5a.5.5 0 0 1 0-1H10V1.5a.5.5 0 0 1 .5-.5M12 3.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m-6.5 2A.5.5 0 0 1 6 6v1.5h8.5a.5.5 0 0 1 0 1H6V10a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5M1 8a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2A.5.5 0 0 1 1 8m9.5 2a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V13H1.5a.5.5 0 0 1 0-1H10v-1.5a.5.5 0 0 1 .5-.5m1.5 2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                        </svg>
                    </Btn>

                </Panel>
                <Panel id="device-panel">
                    <Btn
                        id="device-desktop"
                        togglable={false}
                        context="device"
                        command={{
                            run: (editor: Editor) => {
                                editor.setDevice("Desktop");
                            },
                            stop: () => {
                            }
                        }} active>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
                        </svg>
                    </Btn>
                    <Btn
                        id="device-tablet"
                        togglable={false}
                        context="device"
                        command={{
                            run: (editor: Editor) => {
                                editor.setDevice("Tablet");
                            },
                            stop: () => {
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm-1 8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2z" />
                            <path d="M14 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0" />
                        </svg>
                    </Btn>
                    <Btn
                        id="device-mobile"
                        togglable={false}
                        context="device"
                        command={{
                            run: (editor: Editor) => {
                                editor.setDevice("Mobile");
                            },
                            stop: () => {
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                            <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                        </svg>
                    </Btn>
                </Panel>
                <Panel id="action-panel">
                    <Btn
                        id="code-btn"
                        command={{
                            run: (editor: Editor) => {
                                editor.runCommand("code-editor");
                            }
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0" />
                        </svg>
                    </Btn>
                    <Btn id="save-btn"
                        togglable={false}
                        command={{ run: handleSave }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z" />
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                        </svg>
                    </Btn>
                    <Btn
                        id="preview-btn"
                        togglable={true}
                        command={{
                            run: (editor: Editor) => {
                                editor.runCommand("preview");
                            },
                        }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                        </svg>
                    </Btn>
                </Panel>
            </Navbar>

            <EditorBody>
                <Sidebar>
                    <StyleManager className="" >
                        <SelectorManager />
                    </StyleManager>
                    <BlockManager className="hide" />
                    <TraitsManager className="hide" />
                    <LayerManager className="hide" />
                </Sidebar>

                <EditorCanvas />
            </EditorBody>

            <LoadingScreen width={loadingWidth} error={loadinfError} />
            <LoadingAction show={loadingActionShow} autohide={100} />
        </EditorApp>
    )
}