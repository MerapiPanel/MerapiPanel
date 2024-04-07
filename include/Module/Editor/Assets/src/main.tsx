import React, { StrictMode } from "react";
import ReactDOM from "react-dom/client";
import { App } from "./parts/App";
import { Editor } from "grapesjs";
import "@il4mb/merapipanel/scss/editor";


export type Payload = {
    options: {
        width: string
        height: string
        container: string
        plugins: string[]
    },
    editor: Editor|null,
    setEditor: (editor: Editor) => void
}


const payload = (window as any).editor as Payload;

ReactDOM
    .createRoot(document.querySelector(payload.options.container) as HTMLElement)
    .render(
        <StrictMode>
            <App payload={payload} />
        </StrictMode>
    );