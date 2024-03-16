import React, { useEffect } from "react";
import { Component, Editor } from "grapesjs";
import { PanelProps } from "../_define";
import LayersContainer from "./containers/LayersContainer";
import { Button } from "grapesjs";
import ReactDomServer from "react-dom/server";


// Function to generate breadcrumbs
function generateBreadcrumbs(component: Component): string[] {
    const breadcrumbs: string[] = [];
    let currentComponent: Component = component;

    // Traverse up the component hierarchy until reaching the body
    while (currentComponent) {

        let name: string = currentComponent.get('type') as string;
        if (currentComponent.get("tagName") == "body") {
            name = "body";
        } else if (!name || name?.length == 0) {
            name = currentComponent.get("tagName") as string;
        }
        name = `<span class='component-name'>${name}</span>`;

        if ((currentComponent.get("classes") as any)?.length > 0) {
            name += `<span class='component-class'>`;
            (currentComponent.get("classes") as any).models.forEach((model: any) => {
                name += "." + model.get("name");
            });
            name += "</span>";
        }

        breadcrumbs.unshift(name);
        currentComponent = currentComponent.parent() as Component;
    }

    return breadcrumbs;
}

// Function to update the breadcrumb UI
function updateBreadcrumbUI(component: any) {
    const breadcrumbContainer = document.getElementById('breadcrumb');
    if (!breadcrumbContainer) return;

    breadcrumbContainer.innerHTML = '';

    const breadcrumbs = generateBreadcrumbs(component);

    breadcrumbs.forEach((crumb, index) => {
        const crumbElement = document.createElement('span');
        crumbElement.innerHTML = crumb;
        if (index !== breadcrumbs.length - 1) {
            crumbElement.innerHTML += ' <i class="fas fa-angle-right"></i> ';
        }
        breadcrumbContainer.appendChild(crumbElement);
    });
}


const LayerIcon = () => {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-layers-fill" viewBox="0 0 16 16">
            <path d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882z" />
            <path d="m2.125 8.567-1.86.992a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882l-1.86-.992-5.17 2.756a1.5 1.5 0 0 1-1.41 0z" />
        </svg>
    )
}


const LeftPanel = ({ editor }: PanelProps) => {


    useEffect(() => {
        if (editor === null) return;

        editor.Panels.addPanel({
            id: 'panel-left',
            el: '.layout__panel-left',
        });

        editor.on('component:selected', (component: Component) => {
            updateBreadcrumbUI(component);
        });

        
        editor.Panels.getPanel('panel-actions')?.buttons.add({
            id: "layers",
            className: "btn-open-layers",
            label: `${ReactDomServer.renderToString(<LayerIcon />)}`,
            command: 'open-layers',
            togglable: false,
            active: false
        } as any);


        editor.Commands.add('open-layers', {
            getEl() {
                return document.querySelector('.layout__panel-left')
            },

            run(editor: Editor) {

                console.log(this.getEl());

                if (this.getEl().classList.contains('layout-opened')) {
                    this.getEl().classList.remove('layout-opened');
                    this.getEl().classList.add('layout-closed');
                } else {
                    this.getEl().classList.add('layout-opened');
                    this.getEl().classList.remove('layout-closed');
                }
            }
        });


    }, [editor]);


    return (
        <>
            <div className="layout__panel-left layout-opened">
                <LayersContainer editor={editor} />
                <div className="layers-breadcrumb" id="breadcrumb"></div>
            </div>
        </>
    )
}


export default LeftPanel;