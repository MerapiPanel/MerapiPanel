import React, { useEffect, useRef } from "react";
import { useRoot } from "../RootEditor";
import { Component } from "grapesjs";



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
function updateBreadcrumbUI(component: any, ref: any) {

    const breadcrumbContainer = ref.current;
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



const Breadcrumb = () => {

    const { editor } = useRoot();
    const ref = useRef<HTMLDivElement>(null);

    useEffect(() => {

        if (editor === null) return;
        editor.on('component:selected', (component: Component) => {
            updateBreadcrumbUI(component, ref);
        });
    }, [editor]);

    return (
        <div ref={ref} id="breadcrumb" className="merapi__editor--breadcrumb"></div>
    )

}


export default Breadcrumb;