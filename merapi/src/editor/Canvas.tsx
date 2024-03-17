import React, { useEffect, useRef } from "react";
import { useRoot } from "./RootEditor";

interface CanvasProps {
    children?: React.ReactNode
}


export const Canvas = (props: any) => {

    const { canvasRef } = useRoot();

    return (
        <div ref={canvasRef} className="editor__canvas">
            {props.children}
        </div>
    )
}


export default Canvas;
export type { CanvasProps }