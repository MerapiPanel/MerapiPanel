import React, { useEffect, useState } from 'react';
import { Payload } from '../main';
import { App as EditorApp, Navbar, EditorBody, EditorCanvas, Sidebar, LoadingScreen, LoadingAction } from '@il4mb/merapipanel/editor';
import { PanelProvider, usePanelContext } from './PanelProvider';
import { PanelRegister } from './PanelRegister';
import { Btn, Panel } from '@il4mb/merapipanel/editor/partial';
import { AddComponentTypeOptions, BlockProperties, CommandObject, Editor } from 'grapesjs';
import { CodeEditor, Bootstrap5 } from '@il4mb/merapipanel/editor/plugins';
import $ from 'jquery';
import { toast } from '@il4mb/merapipanel/toast';
import { fileManager } from '../parts/FileManager';


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

    for (const i in blocks) {

        const blockDefine: BlockDefine = blocks[i];
        if (!blockDefine.index) {
            console.error(`Cant register block ${i}`);
            continue;
        }

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
                        if (modelCallback.updated) modelCallback.updated.call(this, prop, val, prev);
                    }
                },
                view: {
                    render() {
                        const components = this.model.components();
                        if (!components || components.length <= 0) {
                            this.el.innerHTML = "";
                            this.el.appendChild($(`<div class='text-center py-3' style="opacity: 0.5;">${this.model.get('type')}</div>`)[0]);
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

    editor.refresh();
    setTimeout(() => setLoadingWidth(100), 1000);

}


export const App = ({ payload }: { payload: Payload }) => {
    const { buttons = [], layers = [] } = usePanelContext();

    const [loadingWidth, setLoadingWidth] = useState(10);
    const [loadingError, setLoadingError] = useState(false);
    const [loadingActionShow, setLoadingActionShow] = useState(false);

    const devices = [
        { name: "Desktop", width: "" },
        { name: "Tablet", width: "768px", widthMedia: "1024px" },
        { name: "Mobile", width: "320px", widthMedia: "480px" },
    ];



    const onReadyHandler = (editor: Editor) => {

        editor.on("asset:custom", fileManager);
        setLoadingWidth(35);
        try {

            if (payload.fetchBlockURL) {
                fetch(payload.fetchBlockURL)
                    .then(res => res.json())
                    .then(res => ___InitRegisterBlocks(editor, res.data, setLoadingWidth))
                    .finally(() => {

                        editor.Keymaps.add('ns:my-keymap', '⌘+s, ctrl+s', () => {
                            handleSave(editor);
                            return false;
                        }, {
                            // Prevent the default browser action
                            prevent: true,
                        });

                        setTimeout(() => {
                            if (typeof payload.onReady === "function") {
                                payload.onReady(editor);
                            }
                        }, 50);
                    })
            } else {
                console.error("fetchBlockURL is not defined");
                editor.Keymaps.add('ns:my-keymap', '⌘+s, ctrl+s', () => {
                    handleSave(editor);
                    return false;
                }, {
                    // Prevent the default browser action
                    prevent: true,
                });

                setTimeout(() => {
                    if (typeof payload.onReady === "function") {
                        payload.onReady(editor);
                    }
                }, 50);
            }


        } catch (error) {
            console.error(error);
            setLoadingError(true);
        }

        editor.on("component:selected", (component) => {
            if (component.get("stylable") === false) {
                $("div.editor-layout.style-manager").addClass("d-none");
            } else {
                $("div.editor-layout.style-manager").removeClass("d-none");
            }
        })

        // Remove duplicate elements in panels
        $(".editor-panel").each(function () {
            let seen = {}; // Object to keep track of seen elements
            $(this).children().each(function () {
                let text = $(this).text(); // Assuming the text content uniquely identifies the element
                if (seen[text]) {
                    $(this).remove(); // Remove the duplicate element
                } else {
                    seen[text] = true; // Mark this element as seen
                }
            });
        });
    }

    const handleSave = (editor: Editor) => {
        // Save handler code...
    };

    useEffect(() => {
        console.log(buttons);
    }, [buttons]);

    return (
        <>
            <EditorApp
                deviceManager={{ devices }}
                plugins={payload.config.plugins || [CodeEditor, Bootstrap5, (editor) => editor.onReady(onReadyHandler)]}
                canvas={(payload.config.canvas || {}) as any}
                storageManager={{ autosave: false }}
                pluginsOpts={payload.config.pluginsOpts || {}}
                assetManager={payload.config.assetManager}
            >
                <Navbar>
                    <Panel id="sidebar-panel">
                        {Object.entries(buttons)?.map(([id, element]) => (
                            <React.Fragment key={id}>{element}</React.Fragment>
                        ))}
                    </Panel>
                    <Panel id="device-panel">
                        {/* Device buttons... */}
                    </Panel>
                    <Panel id="action-panel">
                        {/* Action buttons... */}
                    </Panel>
                </Navbar>
                <EditorBody>
                    <Sidebar id="sidebar">
                        {Object.entries(layers)?.map(([id, element]) => (
                            <React.Fragment key={id}>{element}</React.Fragment>
                        ))}
                    </Sidebar>
                    <EditorCanvas />
                </EditorBody>
                <LoadingScreen width={loadingWidth} error={loadingError} />
                <LoadingAction show={loadingActionShow} autohide={100} />
            </EditorApp>
            <PanelRegister
                id="style"
                icon={<i className="fa-regular fa-file-code"></i>}
            >
                <h1>Hello World</h1>
            </PanelRegister>
        </>
    );
};
