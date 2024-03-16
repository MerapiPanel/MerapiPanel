import React, { useState, StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import LayoutMain from './editor/LayoutMain';
import { DeepMerge, OptionsProvider } from './editor/provider/Options';
import LoadingScreen from './editor/component/LoadingScreen';
import RootEditor from './editor/RootEditor';
import Panel from './editor/component/Panel';
import Layout from './editor/Layout';
import LayoutRow from './editor/LayoutRow';
import Canvas from './editor/Canvas';


const MP_Editor = () => {


    // return (
    //     <>
    //         <OptionsProvider>
    //             <div className="container__editor">
    //                 <LayoutMain />
    //             </div>
    //             <LoadingScreen />
    //         </OptionsProvider>
    //     </>
    // );

    return (
        <RootEditor>
            <Layout>
                <Panel></Panel>
                <Panel></Panel>
                <Panel></Panel>
            </Layout>
            <LayoutRow>
                <Layout></Layout>
                <Canvas></Canvas>
                <Layout></Layout>
            </LayoutRow>
        </RootEditor>
    )
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_Editor />
    </StrictMode>
)