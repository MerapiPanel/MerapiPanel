import React, { useEffect, useRef, useState } from "react";
import { useContainer } from "./container";
import { Widget } from "./widget";




export const MenuGroup = ({ name, items = [], isOpen = false }) => {
    const [open, setOpen] = useState(isOpen);

    return (
        <div className={`menu-group${open ? ' open' : ''}`}>
            <div className="menu-group-name" onClick={() => setOpen(!open)}>{name}</div>
            <div className="menu-group-items">
                {items.map((item, index) => {
                    return (
                        <MenuItems key={index} name={item.name} title={item.title} description={item.description} icon={item.icon} option={item.option} />
                    )
                })}
            </div>
        </div>
    )
}






export const MenuItems = ({ name, title, icon = `<i class="fa-regular fa-face-smile"></i>`, description, option }) => {

    const { setOpenMenu, addContent, setChanged } = useContainer();

    const handleClick = () => {

        console.log(name, title, icon, description, option)
        setOpenMenu(false);
        setChanged(true);
        addContent(<Widget
            id={(new Date().getTime()).toString(36)}
            name={name}
            title={title}
            description={description}
            option={Object.assign({ width: 200, height: 100 }, option || {})}
            focus={true} />);
    }



    if (icon.startsWith("<svg")) {
        const viewBox = icon.match(/viewBox="([^"]+)"/)[1];
        icon = icon.replace(/\<svg.*\>/, "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"22\" height=\"22\" fill=\"currentColor\" viewBox=\"" + viewBox + "\">");
    }


    return (
        <div className="menu-item" data-name={name} onClick={handleClick}>
            <div className="menu-item-icon" dangerouslySetInnerHTML={{ __html: icon }}></div>
            <div className="menu-item-title">{title}</div>
            <div className="menu-item-description">{description}</div>
        </div>
    )
}




export const WidgetMenu = ({ children }) => {

    const { isEdit, openMenu, setOpenMenu } = useContainer();
    const ref = useRef(null);
    const [items, setItems] = useState([]);

    useEffect(() => {
        if (!isEdit) {
            setOpenMenu(false);
        } else {
            fetchMenu();
        }
    }, [isEdit]);


    const fetchMenu = () => {

        const { endpoint_edit, endpoint_save, endpoint_load } = window.widgetPayload;

        fetch(endpoint_edit).then((response) => response.json()).then((response) => {
            const stack = {};
            if (response.data && Array.isArray(response.data)) {
                response.data.forEach((item) => {
                    if (!stack[(item.category || 'default').toLowerCase()]) {
                        stack[(item.category || 'default').toLowerCase()] = [];
                    }
                    stack[(item.category || 'default').toLowerCase()].push(item);
                })
            }
            setItems(stack);
        });
    }



    return (
        <div ref={ref} className={`widget-menu${openMenu ? ' open' : ''}`}>
            {Object.keys(items).map((key, index) => {
                return (

                    <MenuGroup key={index} name={key} items={items[key]} isOpen={index === 0} />
                )
            })}
        </div>
    )
}