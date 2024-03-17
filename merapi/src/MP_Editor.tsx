import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import RootEditor from './editor/RootEditor';
import Panel from './editor/component/Panel';
import Layout from './editor/Layout';
import LayoutRow from './editor/LayoutRow';
import Canvas from './editor/Canvas';
import Button from './editor/component/Button';
import { Editor } from 'grapesjs';
import LayersContainer from './editor/component/containers/LayersContainer';
import SelectedContainer from './editor/component/containers/SelectedContainer';
import StylesContainer from './editor/component/containers/StylesContainer';
import Breadcrumb from './editor/component/Breadcrumb';
import TraitsContainer from './editor/component/containers/TraitsContainer';
import { MobileIcon, DesktopIcon, TabletIcon, TraitsIcon, LayersIcon, StyleIcon, VisibilityIcon, CodeIcon } from './editor/component/Icons';
import "./editor/style/main.scss";
import LoadingScreen from './editor/component/LoadingScreen';



const MP_Editor = () => {

    const config = {
        deviceManager: {
            devices: [{
                name: 'Desktop',
                width: ''
            }, {
                name: 'Tablet',
                width: '768px'
            }, {
                name: 'Mobile',
                width: '320px'
            }]
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
        });
        editor.Commands.add('set-device-mobile', {
            run: editor => editor.setDevice('Mobile'),
            stop: () => { }
        });
        editor.Commands.add('sw-layers-toggle', {
            run: (editor: Editor) => {
                document.getElementById("layout-left")?.classList.remove('hide');
            },
            stop: () => {
                document.getElementById("layout-left")?.classList.add('hide');
            }
        });

        editor.Commands.add("sw-traits", {
            run: (editor: Editor) => {

            }
        });

        editor.select(editor.getWrapper());
    }

    return (
        <RootEditor onReady={onReadyHandle} config={config}>

            <Layout className='layout-top'>

                <Panel id='panel-top-left'>
                    <Button id='visibility' className='border' command='sw-visibility' active >
                        <VisibilityIcon />
                    </Button>
                    <Button id='export' className='border' command='export-template' context='export-template' >
                        <CodeIcon />
                    </Button>
                    <Button id='layers-toggle' className='border' command='sw-layers-toggle' context='layers' >
                        <LayersIcon />
                    </Button>
                </Panel>

                <Panel id='panel-top-center'>
                    <Button id='device-desktop' command='set-device-desktop' togglable={false} active>
                        <DesktopIcon />
                    </Button>
                    <Button id='device-tablet' command='set-device-tablet' togglable={false}>
                        <TabletIcon />
                    </Button>
                    <Button id='device-mobile' command='set-device-mobile' togglable={false}>
                        <MobileIcon />
                    </Button>
                </Panel>

                <Panel id='panel-top-right'>

                </Panel>

            </Layout>

            <LayoutRow>

                <Layout className='layout-left hide' id='layout-left'>
                    <LayersContainer />
                </Layout>

                <Canvas></Canvas>

                <Layout className='layout-right'>

                    <SelectedContainer />

                    <Panel id='panel-style' className='panel-group'>

                        <Button id='btn-style'
                            active
                            togglable={false}
                            command={{
                                run: (editor: Editor) => {
                                    (document.getElementById('container-styles') as any).style.display = '';
                                },
                                stop: () => {
                                    (document.getElementById('container-styles') as any).style.display = 'none';
                                }
                            }}  >
                            <StyleIcon />
                        </Button>

                        <Button id='btn-traits'
                            togglable={false}
                            command={{
                                run: (editor: Editor) => {
                                    (document.getElementById('container-traits') as any).style.display = '';
                                },
                                stop: () => {
                                    (document.getElementById('container-traits') as any).style.display = 'none';
                                }
                            }} >
                            <TraitsIcon />
                        </Button>

                    </Panel>

                    <StylesContainer id='container-styles' />
                    <TraitsContainer id='container-traits' />

                </Layout>
            </LayoutRow>

            <Breadcrumb />
            <LoadingScreen />
        </RootEditor>
    )
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_Editor />
    </StrictMode>
)