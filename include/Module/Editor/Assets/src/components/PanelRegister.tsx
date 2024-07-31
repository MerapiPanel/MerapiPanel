import React, { useEffect } from 'react';
import { usePanelContext } from './PanelProvider';
import { TPanelRegister } from './PanelProvider';

export const PanelRegister: React.FC<TPanelRegister> = ({ id, icon, children }) => {
    const { addPanel } = usePanelContext();

    useEffect(() => {
        addPanel(id, icon, children);
    }, [id, icon, children, addPanel]);

    return null;
};
