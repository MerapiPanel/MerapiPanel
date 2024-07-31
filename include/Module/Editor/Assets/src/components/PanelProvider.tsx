import { Btn, Panel } from '@il4mb/merapipanel/editor/partial';
import React, { createContext, useContext, useState, useEffect } from 'react';

export type TPanelRegister = {
    id: string
    icon: React.JSX.Element
    children?: React.JSX.Element
}

type TKeyObject<T> = {
    [key: string]: T
}

type TPanelContext = {
    buttons: TKeyObject<React.JSX.Element>
    layers: TKeyObject<React.JSX.Element>
    addPanel: (id: string, icon: React.JSX.Element, children?: React.JSX.Element) => void
}

// Create a context
const PanelContext = createContext<TPanelContext>({} as any);

export const usePanelContext = () => useContext(PanelContext);

export const PanelProvider = ({ children }) => {
    const [buttons, setButtons] = useState<TKeyObject<React.JSX.Element>>({});
    const [layers, setLayers] = useState<TKeyObject<React.JSX.Element>>({});

    const addPanel = (id: string, icon: React.JSX.Element, children?: React.JSX.Element) => {
        setButtons(prevButtons => {
            if (prevButtons[id]) {
                // If the ID already exists, do not add it again
                return prevButtons;
            }
            return {
                ...prevButtons,
                [id]: (
                    <Btn
                        key={id}
                        id={id}
                        togglable={false}
                        context="sidebar"
                        command={{
                            run: () => {
                                document.querySelector("#panel-" + id)?.classList.remove("hide");
                            },
                            stop: () => {
                                document.querySelector("#panel-" + id)?.classList.add("hide");
                            }
                        }}
                        active
                    >
                        {icon}
                    </Btn>
                )
            };
        });

        setLayers(prevLayers => {
            if (prevLayers[id]) {
                // If the ID already exists, do not add it again
                return prevLayers;
            }
            return {
                ...prevLayers,
                [id]: (
                    <div key={id} id={"panel-" + id} className=''>
                        {children}
                    </div>
                )
            };
        });
    }

    return (
        <PanelContext.Provider value={{ buttons, layers, addPanel }}>
            {children}
        </PanelContext.Provider>
    );
};
