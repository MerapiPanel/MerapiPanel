import React, { useEffect, useRef } from "react";
import { useRoot } from "./RootEditor";

interface CanvasProps {
    //reff?: React.MutableRefObject<any>
}


export const Canvas = (props: any) => {

    const { canvasRef } = useRoot();

    useEffect(() => {
        //console.log(ref.current);
    }, []);

    return (
        <div ref={canvasRef} className="editor__canvas">
            <h1>Canvas</h1>
            <div className="image-container">
                <img src="https://picsum.photos/200/300" alt="Random Image" />
            </div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas amet quia atque sunt, maiores ducimus, earum non minus provident quas dolores. Modi neque nostrum aut nihil dolorem reiciendis officia ab!</p>
        </div>
    )
}


export default Canvas;
export type { CanvasProps }