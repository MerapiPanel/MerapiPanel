import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import RootEditor from './editor/RootEditor';
import Panel from './editor/component/Panel';
import Layout from './editor/Layout';
import LayoutRow from './editor/LayoutRow';
import Canvas from './editor/Canvas';
import Container from './editor/Container';
import Button from './editor/Button';
import { Editor } from 'grapesjs';
import "./editor/style.scss";



const MobileIcon = () => {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" >
            <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
            <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
        </svg >
    )
}

const TabletIcon = () => {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm-1 8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2z" />
            <path d="M14 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0" />
        </svg>
    )
}

const DesktopIcon = () => {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
        </svg>
    )
}

const MP_Editor = () => {

    const config = {
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
    }

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

    const onReadyHandle = (editor: Editor) => {
        editor.Commands.add('set-device-desktop', {
            run: editor => editor.setDevice('Desktop'),
            stop: () => { }
        });
        editor.Commands.add('set-device-tablet', {
            run: editor => editor.setDevice('Tablet'),
            stop: () => { }
        })
        editor.Commands.add('set-device-mobile', {
            run: editor => editor.setDevice('Mobile'),
            stop: () => { }
        });
    }

    return (
        <RootEditor onReady={onReadyHandle} config={config}>

            <Layout className='layout-top'>

                <Panel id='panel-actions'>
                    <Button id='visibility' label='visibility' command='sw-visibility' active />
                    <Button id='btn-open' label='delete' command='delete' />
                    <Button id='btn-save' label='initial' command='initial' />
                </Panel>

                <Panel id='panel-devices'>
                    <Button id='device-desktop' label={<DesktopIcon />} command='set-device-desktop' active />
                    <Button id='device-tablet' label={<TabletIcon />} command='set-device-tablet' />
                    <Button id='device-mobile' label={<MobileIcon />} command='set-device-mobile' />
                </Panel>

                <Panel id='panel-settings'>
                    <Button id='btn-undo' label='undo' command='undo' />
                    <Button id='btn-redo' label='redo' command='redo' />
                </Panel>

            </Layout>


            <LayoutRow>

                <Layout className='layout-left'>
                    <Container></Container>
                </Layout>

                <Canvas></Canvas>

                <Layout className='layout-right'>

                </Layout>
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