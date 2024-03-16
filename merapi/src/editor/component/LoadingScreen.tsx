import React, { useState, createContext, useContext, useEffect } from "react";
import { useOptions } from "../provider/Options";


const LoadingScreen = () => {

    const { progress, setOptions } = useOptions();


    useEffect(() => {

        console.log("PROGRESS", progress);


        if (progress === 100) {
            document.querySelector('.loading-screen')?.classList.add('hide');
            setTimeout(() => {
                document.querySelector('.loading-screen')?.remove();
            }, 400);
        }
    }, [progress]);

    

    return (
        <div className="loading-screen">
            <div className="loading-progress">
                <div className="progressbar" style={{ "--MP-loading-width": `${progress}%` } as any}></div>
            </div>
        </div>
    )
}

export default LoadingScreen;
