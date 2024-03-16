import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import { OptionsProvider } from './editor/provider/Options';
import LayoutMain from './editor/LayoutMain';
import LoadingScreen from './editor/component/LoadingScreen';


const MP_ContentEditor = () => {

    return (
        <>
            <OptionsProvider>
                <div className="container__editor">
                    <LayoutMain />
                </div>
                <LoadingScreen />
            </OptionsProvider>
        </>
    );
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_ContentEditor />
    </StrictMode>
)