import React, { StrictMode } from 'react';
import { createRoot } from "react-dom/client";
import { Editor } from 'grapesjs';
import { RootElement, Panel, Layout, LayoutRow, Button, Canvas, Icons, LayersContainer, BlocksContainer, Breadcrumb, LoadingScreen, useRoot, SelectedContainer, StylesContainer, TraitsContainer } from "@il4mb/merapipanel";
import { fetchWithProgress } from "@il4mb/merapipanel/tools";
import "@il4mb/merapipanel/editor/style";
import { RootConfig } from '@il4mb/merapipanel/dist/editor/Root';


const App = () => {

    const config = {
        width: "100vw",
        height: "100vh",
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
            .then(((json: any) => json.data?.map(async (item: any) => await import(/*webpackIgnore: true*/(item.index as string)))))
            .catch((error: any) => {
                console.error('Fetch error:', error); // Handling any errors that occur during fetch
            });
    }

    return (
        <RootElement onReady={onReadyHandle} config={config}>

            <Canvas id='center-group'>
                <h1>Hallo World</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                <div>
                    <img src="https://via.placeholder.com/300" alt="" />
                </div>
            </Canvas>

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