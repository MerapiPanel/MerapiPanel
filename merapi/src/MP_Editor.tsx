import React, { useState, StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import LayoutMain from './editor/LayoutMain';


const MP_Editor = () => {



    return (
        <>
            <div className="container__editor">
                <LayoutMain />
            </div>
        </>
    );
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_Editor />
    </StrictMode>
)