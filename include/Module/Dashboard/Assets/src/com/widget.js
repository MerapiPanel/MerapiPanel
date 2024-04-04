import React, { useEffect, useRef, useState } from "react";
import { useContainer } from "./container";


export const Widget = ({ id, name, title, description, option = {
    width: 200,
    height: 200
}, focus = false }) => {

    const { isEdit, contents, setContents, containerRef, setChanged } = useContainer();
    const [isFocus, setIsFocus] = useState(focus);
    const ref = useRef();
    const [isResizeX, setIsResizeX] = useState(false);
    const [isResizeY, setIsResizeY] = useState(false);
    const { endpoint_load, endpoint_save, endpoint_edit } = window.widgetPayload;


    useEffect(() => {
        const documentClickHandle = (e) => {
            if (isResizeX || isResizeY) return;
            if (ref && ref.current && !ref.current.contains(e.target)) {
                setIsFocus(false);
            }
        }

        setTimeout(() => document.addEventListener('click', documentClickHandle), 200);

        return () => {
            setTimeout(() => document.removeEventListener('click', documentClickHandle), 200);
        }
    });


    const persentage = (current, max) => {
        return Math.round((current / max) * 100);
    }



    const clickHandle = () => {
        if (isResizeX || isResizeY) return;
        setIsFocus(last => !last);
        if (!isFocus) {
            setIsResizeX(false);
        }
    }

    var initialX = 0;
    const resizeXStart = (e) => {
        // e.preventDefault(); // Prevent default touch behavior
        setIsResizeX(true);
        const eventMove = e.type === 'touchstart' ? 'touchmove' : 'mousemove';
        const eventEnd = e.type === 'touchstart' ? 'touchend' : 'mouseup';
        initialX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        document.addEventListener(eventMove, resizeXHandle);
        document.addEventListener(eventEnd, resizeXEnd);

    };

    const resizeXHandle = (e) => {

        const movementX = e.type === 'touchmove' ? e.touches[0].clientX - initialX : e.movementX;
        ref.current.style.width = `${ref.current.offsetWidth + movementX}px`;
        option.width = ref.current.offsetWidth + movementX;
        initialX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        setChanged(true);
    };

    const resizeXEnd = (e) => {
        const persentageX = persentage(ref.current.offsetWidth, containerRef.current.offsetWidth);
        option.width = ref.current.offsetWidth;
        ref.current.style.width = `${persentageX}%`;
        const eventMove = e.type === 'touchend' ? 'touchmove' : 'mousemove';
        const eventEnd = e.type === 'touchend' ? 'touchend' : 'mouseup';
        document.removeEventListener(eventMove, resizeXHandle);
        document.removeEventListener(eventEnd, resizeXEnd);
        setTimeout(() => {
            setIsResizeX(false);
            setIsFocus(true);
        }, 200)
    };

    var initialY = 0;
    const resizeYStart = (e) => {
        // e.preventDefault(); // Prevent default touch behavior
        setIsResizeY(true);
        const eventMove = e.type === 'touchstart' ? 'touchmove' : 'mousemove';
        const eventEnd = e.type === 'touchstart' ? 'touchend' : 'mouseup';
        initialY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        document.addEventListener(eventMove, resizeYHandle);
        document.addEventListener(eventEnd, resizeYEnd);
    };

    const resizeYHandle = (e) => {
        // e.preventDefault(); // Prevent default touch behavior
        const movementY = e.type === 'touchmove' ? e.touches[0].clientY - initialY : e.movementY;
        ref.current.style.height = `${ref.current.offsetHeight + movementY}px`;
        option.height = ref.current.offsetHeight + movementY;
        initialY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        setChanged(true);
    };

    const resizeYEnd = (e) => {
        const eventMove = e.type === 'touchend' ? 'touchmove' : 'mousemove';
        const eventEnd = e.type === 'touchend' ? 'touchend' : 'mouseup';
        document.removeEventListener(eventMove, resizeYHandle);
        document.removeEventListener(eventEnd, resizeYEnd);
        setTimeout(() => {
            setIsResizeY(false);
            setIsFocus(true);
        }, 200)
    };


    const removeHandle = () => {

        setChanged(true);
        setIsFocus(false);
        setIsResizeX(false);
        setIsResizeY(false);

        const index = contents.findIndex(item => item.props.id === id);
        if (index === -1) {
            return;
        }

        contents.splice(index, 1);
        setContents([...contents]);
    }

    const [isLoaded, setIsLoaded] = useState(false);

    const onLoad = () => {
        setTimeout(() => setIsLoaded(true), 400);
    }

    return (
        <>
            <div ref={ref}
                className={`widget${isFocus ? ' focus' : ''}`}
                data-name={name}
                onClick={clickHandle}
                style={{ width: option.width, height: option.height }}>

                {!isEdit && <>

                    <iframe
                        style={{ display: isLoaded ? 'block' : 'none' }}
                        onLoad={onLoad}
                        className="widget-frame"
                        src={endpoint_load + "/" + name}
                        width="100%"
                        height="100%"
                        frameBorder="0"
                        scrolling={"no"}></iframe>
                    <div className="widget-loading" style={{ display: isLoaded ? 'none' : '' }}>
                        <i className="fa-solid fa-spinner fa-spin-pulse fa-2x"></i>
                        <span className="mt-2">Loading...</span>
                    </div>
                </>}

                {isEdit &&
                    (
                        <>
                            <span>{name}</span>
                            <div className="widget-edit-tool">
                                <button
                                    className="tool-btn tool-delete"
                                    onClick={removeHandle}>
                                    <i className="fa-solid fa-xmark"></i>
                                </button>
                                <button
                                    className="tool-btn tool-resize-x"
                                    onMouseDown={resizeXStart}
                                    onTouchStart={resizeXStart}>
                                    <i className="fa-solid fa-left-right"></i>
                                </button>
                                <button
                                    className="tool-btn tool-resize-y"
                                    onMouseDown={resizeYStart}
                                    onTouchStart={resizeYStart}>
                                    <i className="fa-solid fa-up-down"></i>
                                </button>
                            </div>
                        </>
                    )
                }
            </div>
        </>
    )
}