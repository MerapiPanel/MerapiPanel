import React, { createContext, useState } from "react";
import Container from "./gassets/Container";
import AddButton from "./gassets/AddButton";
export const AppContext = createContext({});

export type TAssetAttribute = {
    key: string,
    value: string
}
export type TAsset = {
    id: string
    name: string
    attributes?: TAssetAttribute[]
    content?: string,
    type: string
}
export const GAssets = () => {

    const [refresh, setRefresh] = useState(true);

    return (
        <>
            <AppContext.Provider value={{
                refresh,
                setRefresh
            }}>
                <Container />
                <AddButton />
            </AppContext.Provider>
        </>
    );
};