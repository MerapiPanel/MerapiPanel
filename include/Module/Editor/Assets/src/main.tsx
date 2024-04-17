import React, { StrictMode } from "react";
import ReactDOM from "react-dom/client";
import { App } from "./parts/App";
import { CanvasConfig, Editor } from "grapesjs";
import "@il4mb/merapipanel/scss/editor";


export type Payload = {
    config: {
        width: string
        height: string
        container: string
        plugins: string[],
        pluginsOptions: { [key: string]: any },
        canvas: CanvasConfig
    }
    fetchBlockURL: string
    callback: (data: any) => void
    onReady: (editor: Editor) => void
    editor: Editor | null,
    setEditor: (editor: Editor) => void
}


const payload = (window as any).editor as Payload;

if (!payload.config.container) {
    payload.config.container = '#editor';
}

ReactDOM
    .createRoot(document.querySelector(payload.config.container) as HTMLElement)
    .render(
        <StrictMode>
            <App payload={payload} />
        </StrictMode>
    );

