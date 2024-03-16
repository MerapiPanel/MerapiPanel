import React, { useEffect, useRef } from "react";
import { useRoot } from "./RootEditor";

interface CanvasProps {
    //reff?: React.MutableRefObject<any>
}


export const Canvas = (props: any) => {
    
    const {canvasRef} = useRoot();

    useEffect(() => {
        //console.log(ref.current);
    }, []);

    return (
        <div ref={canvasRef} className="editor__canvas">
            <h1>Canvas</h1>
        </div>
    )
}


export default Canvas;
export type { CanvasProps }