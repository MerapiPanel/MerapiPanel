import React, { useEffect } from "react";
import ReactDOM from "react-dom/client";
import { Container } from "./com/container";
import { WidgetMenu } from "./com/widget-menu";
import { WidgetContent } from "./com/widget-content";
import "./scss/main.scss";


const App = () => {

    return (
        <Container>
            <WidgetMenu/>
            <WidgetContent/>
        </Container>
    );
};


window.registerWidget = function ( name, component) {
  
    console.log(name, component);
};


ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <App />
    </React.StrictMode>
)