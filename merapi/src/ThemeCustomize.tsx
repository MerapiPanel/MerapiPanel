import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import { Editor } from 'grapesjs';
import { RootElement, Panel, Layout, LayoutRow, Button, Canvas, Icons, LayersContainer, BlocksContainer, Breadcrumb, LoadingScreen, useRoot, SelectedContainer, StylesContainer, TraitsContainer } from "@il4mb/merapipanel";
import { fetchWithProgress } from "@il4mb/merapipanel/tools";
import "@il4mb/merapipanel/editor/style";
import { RootConfig } from '@il4mb/merapipanel/dist/editor/Root';


const App = () => {

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
        }
    }

    const onReadyHandle = (config: RootConfig) => {

        config?.editor?.Commands.add('set-device-desktop', {
            run: editor => editor.setDevice('Desktop'),
            stop: () => { }
        });
        config?.editor?.Commands.add('set-device-tablet', {
            run: editor => editor.setDevice('Tablet'),
            stop: () => { }
        });
        config?.editor?.Commands.add('set-device-mobile', {
            run: editor => editor.setDevice('Mobile'),
            stop: () => { }
        });

        config?.editor?.Commands.add('sw-layers-toggle', {
            run: (editor: Editor) => {
                document.getElementById("container-layers")?.classList.remove('hide');
                document.getElementById("left-group")?.classList.remove('hide');
            },
            stop: () => {
                document.getElementById("container-layers")?.classList.add('hide');
                document.getElementById("left-group")?.classList.add('hide');
            }
        });

        config?.editor?.Commands.add("sw-blocks-toggle", {
            run: (editor: Editor) => {
                document.getElementById("container-blocks")?.classList.remove('hide');
                document.getElementById("left-group")?.classList.remove('hide');
            },
            stop: () => {
                document.getElementById("container-blocks")?.classList.add('hide');
                document.getElementById("left-group")?.classList.add('hide');
            }
        });


        config?.editor?.Commands.add("sw-traits", {
            run: (editor: Editor) => {

            }
        });

        config?.editor?.select(config?.editor?.getWrapper());
        config?.setProgress(100);

        // Assuming window.payload.endpoint contains the URL to fetch from
        fetchWithProgress({
            url: (window as any).endpoints.block,
            onProgress: (loaded: number, total: number) => {
                // This callback will be called as the download progresses
                // console.log(`Progress: ${(loaded / total * 100).toFixed(2)}%`);
            }
        })
            .then(res => res.json())
            .then((async (json: any) => await import(/*webpackIgnore: true*/(json.data[0].index as string))))
            .catch((error: any) => {
                console.error('Fetch error:', error); // Handling any errors that occur during fetch
            });

    }

    return (
        <RootElement onReady={onReadyHandle} config={config}>
            <Layout id='top-group' className='layout-top'>

                <Panel id='panel-top-left'>
                    <Button id='visibility' className='border' command='sw-visibility' active >
                        <Icons.Visibility />
                    </Button>
                    <Button id='export' className='border' command='export-template' context='export-template' >
                        <Icons.Code />
                    </Button>
                    <Button id='layers-toggle' className='border' command='sw-layers-toggle' context='left-panel' >
                        <Icons.Layers />
                    </Button>
                    <Button id='blocks-toggle' className='border' command='sw-blocks-toggle' context='left-panel' >
                        <Icons.GridFill />
                    </Button>
                </Panel>

                <Panel id='panel-top-center'>
                    <Button id='set-device-desktop' className='border' command='set-device-desktop' >
                        <Icons.Desktop />
                    </Button>
                    <Button id='set-device-tablet' className='border' command='set-device-tablet' >
                        <Icons.Tablet />
                    </Button>
                    <Button id='set-device-mobile' className='border' command='set-device-mobile' >
                        <Icons.Mobile />
                    </Button>
                </Panel>

                <Panel id='panel-top-right'></Panel>

            </Layout>

            <LayoutRow>
                <Layout className='layout-left hide' id='left-group'>
                    <LayersContainer id='container-layers' />
                    <BlocksContainer id='container-blocks' />
                </Layout>

                <Canvas id='center-group'>
                    <h1>Hallo World</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
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
                            <Icons.Style />
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
                            <Icons.Traits />
                        </Button>

                    </Panel>

                    <StylesContainer id='container-styles' />
                    <TraitsContainer id='container-traits' />
                </Layout>

            </LayoutRow>
            <Breadcrumb />
            <LoadingScreen />
        </RootElement>
    )
};



const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <StrictMode>
        <App />
    </StrictMode>
)