import React, { useContext, useEffect } from "react";
import { AppContext } from "../gassets";

interface IListenner {
    onRefresh?: Function
    onChanged?: Function
}

const Listenner = ({ onRefresh, onChanged }: IListenner) => {
    const { refresh } = useContext(AppContext);
    const { changed } = useContext(AppContext);
    useEffect(() => {
        if (onRefresh instanceof Function && refresh) {
            onRefresh({
                refresh
            });
        }
    }, [refresh]);
    useEffect(() => {
        if (onChanged instanceof Function && changed) {
            onChanged({ changed });
        }
    }, [changed]);
    return (<></>);
}

export default Listenner;