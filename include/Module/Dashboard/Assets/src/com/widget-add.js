import React, { useEffect, useRef } from "react";
import { useContainer } from "./container";

export const WidgetAdd = ({ }) => {

    const { isEdit, setOpenMenu } = useContainer();
    const ref = useRef(null);

    const handleClick = () => {
        setOpenMenu(current => !current);
    }

    return (
        <>
            {isEdit &&
                <div ref={ref} className="widget-add" onClick={handleClick}>
                    <i className="fa-solid fa-plus fa-x2"></i>
                    <span className="ms-1">Add Widget</span>
                </div>}
        </>
    )
}