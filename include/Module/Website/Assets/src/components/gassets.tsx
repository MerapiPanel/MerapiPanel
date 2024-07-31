import React, { createContext, useState } from "react";
import Container from "./gassets/Container";
import AddButton from "./gassets/AddButton";

interface IContextApp {
    refresh: boolean
    setRefresh: React.Dispatch<React.SetStateAction<boolean>>
    changed?: IGAssets | null
    setChanged: React.Dispatch<React.SetStateAction<IGAssets | null>>
}
export const AppContext = createContext<IContextApp>({} as IContextApp);


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
interface IGAssets {
    children?: React.JSX.Element
}
export const GAssets = ({ children }: IGAssets) => {

    const [refresh, setRefresh] = useState(true);
    const [changed, setChanged] = useState<IGAssets | null>(null);

    return (
        <>
            <AppContext.Provider value={{
                refresh,
                setRefresh,
                changed,
                setChanged
            }}>
                <Container />
                <AddButton />
                {children}
            </AppContext.Provider>
        </>
    );
};