import * as dialog from "@il4mb/merapipanel/dialog";
import React, { useEffect, useRef, useState, createContext, useContext } from "react";

const ContainerContext = createContext({});

export const useContainer = () => {
    return useContext(ContainerContext);
}

export const Container = ({ children }) => {

    const [isEdit, setEdit] = useState(false);
    const containerRef = useRef(null);
    const [contents, setContents] = useState([]);
    const [openMenu, setOpenMenu] = useState(false);
    const [isChanged, setChanged] = useState(false);


    useEffect(() => {
        const editToggle = document.getElementById('edit-widget-button');
        if (editToggle) {
            const handleClick = () => {
                console.log(isEdit, isChanged)
                if (isEdit && isChanged) {
                    dialog.confirm('<h4><i class="fa-solid fa-triangle-exclamation"></i> Unsaved changes</h4>', 'You have unsaved changes.<br>Are you sure you want to discard them?')
                    .then((result) => {
                        if (result) {
                            setEdit(prevIsEdit => !prevIsEdit); // Toggle isEdit
                        }
                    })
                } else {
                    setEdit(prevIsEdit => !prevIsEdit); // Toggle isEdit
                }

            };
            editToggle.addEventListener('click', handleClick);

            return () => {
                editToggle.removeEventListener('click', handleClick); // Cleanup listener
            };
        }
    }, [isChanged, isEdit]);

    useEffect(() => {
        if (containerRef.current) {
            if (isEdit) {
                containerRef.current.classList.add('widget-editing');
                document.getElementById('edit-widget-button')?.classList.add('active');
            } else {
                containerRef.current.classList.remove('widget-editing');
                document.getElementById('edit-widget-button')?.classList.remove('active');
            }
        }
    }, [isEdit]);


    const store = {
        isChanged,
        setChanged,
        isEdit,
        setEdit,
        containerRef,
        contents: contents,
        addContent: (content) => {
            setContents([...contents, content]);
        },
        setContents,
        openMenu,
        setOpenMenu
    };


    return (
        <ContainerContext.Provider value={store}>
            <div ref={containerRef} className="widget-container">
                {children}
            </div>
        </ContainerContext.Provider>
    )
};