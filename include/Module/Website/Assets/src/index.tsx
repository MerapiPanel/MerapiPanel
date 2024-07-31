import React, { createContext, useState } from "react";
import ReactDOM from "react-dom/client";
import { GAssets } from "./components/gassets";


ReactDOM.createRoot(document.getElementById('assets-root') as any).render(
    <React.StrictMode>
        <GAssets />
    </React.StrictMode>
)