import React, { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { RootElement, Layout, Canvas } from "@il4mb/merapipanel";
import "@il4mb/merapipanel/editor/style";



const root = createRoot(document.getElementById('root') as any);

root.render(
    <StrictMode>
        <RootElement>
            <Layout >
                <div>hallo</div>
            </Layout>
            <Layout>
                <Canvas>
                    <h1>Hallo World</h1>
                </Canvas>
            </Layout>
        </RootElement>
    </StrictMode>
)