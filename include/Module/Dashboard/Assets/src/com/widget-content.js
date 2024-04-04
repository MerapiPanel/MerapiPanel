import React, { useEffect, useState, useRef } from "react";
import { useContainer } from "./container";
import { WidgetAdd } from "./widget-add";
import { http, toast } from "@il4mb/merapipanel";
import { Widget } from "./widget";



const SaveButton = () => {

    const { contents, setChanged } = useContainer();
    const { endpoint_save, endpoint_load } = window.widgetPayload;

    const saveHandle = () => {

        if (!endpoint_save) {
            return console.error('endpoint_save not found');
        }
        if (Array.isArray(contents)) {
            const contentJson = JSON.stringify(contents.map((item) => item.props));
            http.post(endpoint_save, { data: contentJson })
                .then((data) => {
                    setChanged(false);
                    toast("Widget Saved", 5, 'success');
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    }

    return (
        <div className="save-button" onClick={saveHandle}>
            <i className="fa-solid fa-floppy-disk"></i>
        </div>
    )
}


export const WidgetContent = ({ children }) => {

    const { endpoint_save, endpoint_load } = window.widgetPayload;
    const { isEdit, contents, setContents, addContent } = useContainer();
    const ref = useRef(null);

    useEffect(() => {
        http.get(endpoint_load).then((response) => {
            if (response.data) {
                if (Array.isArray(response.data)) {
                    const tempContents = [];
                    response.data.forEach((item) => {
                        tempContents.push(<Widget
                            name={item.name}
                            title={item.title}
                            description={item.description || ''}
                            option={item.option || { width: 200, height: 100 }}
                            icon={item.icon || ''} />);
                    });
                    setContents(tempContents);
                }
            }
        })
    }, [])




    return (
        <div ref={ref} className="widget-content">
            {contents.map((widget, index) => {
                return (
                    <React.Fragment key={index}>
                        {widget}
                    </React.Fragment>
                )
            })}
            <WidgetAdd />
            {isEdit && <SaveButton />}
        </div>
    )
}