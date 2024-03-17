import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import RootEditor from './editor/RootEditor';
import Layout from './editor/Layout';
import Panel from './editor/component/Panel';
import Button from './editor/component/Button';
import { CodeIcon, DesktopIcon, GridFillIcon, LayersIcon, MobileIcon, StyleIcon, TabletIcon, TraitsIcon, VisibilityIcon } from './editor/component/Icons';
import LayoutRow from './editor/LayoutRow';
import LayersContainer from './editor/component/containers/LayersContainer';
import Canvas from './editor/Canvas';
import SelectedContainer from './editor/component/containers/SelectedContainer';
import { Editor } from 'grapesjs';
import StylesContainer from './editor/component/containers/StylesContainer';
import TraitsContainer from './editor/component/containers/TraitsContainer';
import Breadcrumb from './editor/component/Breadcrumb';
import LoadingScreen from './editor/component/LoadingScreen';
import "./editor/style/main.scss";
import BlocksContainer from './editor/component/containers/BlocksContainer';


const MP_ContentEditor = () => {

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
                document.getElementById("container-layers")?.classList.remove('hide');
                document.getElementById("left-group")?.classList.remove('hide');
            },
            stop: () => {
                document.getElementById("container-layers")?.classList.add('hide');
                document.getElementById("left-group")?.classList.add('hide');
            }
        });

        editor.Commands.add("sw-blocks-toggle", {
            run: (editor: Editor) => {
                document.getElementById("container-blocks")?.classList.remove('hide');
                document.getElementById("left-group")?.classList.remove('hide');
            },
            stop: () => {
                document.getElementById("container-blocks")?.classList.add('hide');
                document.getElementById("left-group")?.classList.add('hide');
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
                    <Button id='layers-toggle' className='border' command='sw-layers-toggle' context='left-panel' >
                        <LayersIcon />
                    </Button>
                    <Button id='blocks-toggle' className='border' command='sw-blocks-toggle' context='left-panel' >
                        <GridFillIcon />
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

                <Layout className='layout-left' id='left-group'>
                    <LayersContainer id='container-layers' />
                    <BlocksContainer id='container-blocks' />
                </Layout>

                <Canvas>
                    <h1>Canvas</h1>
                    <div className="image-container">
                        <img src="https://picsum.photos/200/300" alt="Random Image" />
                    </div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas amet quia atque sunt, maiores ducimus, earum non minus provident quas dolores. Modi neque nostrum aut nihil dolorem reiciendis officia ab!</p>
                </Canvas>

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
    );
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <MP_ContentEditor />
    </StrictMode>
)