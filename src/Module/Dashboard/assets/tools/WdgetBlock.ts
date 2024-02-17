/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */

import { WdgetEntity } from "./WdgetEntity";
import { Wdget, WgetEditing } from "./Widget";

type WdgetBlockType = {
    name: string;
    title: string;
    content: string;
    attribute: object;
}



class WdgetBlock implements WdgetEntity, WdgetBlockType {


    el: JQuery<HTMLElement> | undefined;
    name: string;
    title: string;
    content: string;
    attribute: object;
    wdget: Wdget;


    constructor(wdget: Wdget, {
        name,
        title,
        content,
        attribute
    }: WdgetBlockType) {


        this.wdget = wdget;
        this.name = name;
        this.title = title;
        this.content = content;
        this.attribute = attribute;
        if (this.attribute === undefined) {
            this.attribute = {
                width: "200",
                height: "100"
            };
        }
    }





    showEditTools() {

        if ($(document).find(".widget-block.edit-tools").length > 0) {
            return;
        }

        const el = $(this.el as JQuery<HTMLElement>);
        el.data("instance", this);

        const containerEditool = $(`<div class="widget-block edit-tools"></div>`);
        const horizontalHandler = $('<button title="Resize horizontally" class="edit-tool resize-horizontal"><i class="fa-solid fa-left-right"></i></button>');
        const verticalHandler = $('<button title="Resize vertically" class="edit-tool resize-vertical"><i class="fa-solid fa-up-down"></i></button>');
        const cancelHandler = $('<button title="Remove item" class="edit-tool resize-remove"><i class="fa-solid fa-xmark"></i></button>');

        containerEditool.insertBefore(this.el as JQuery<HTMLElement>).append(this.el as JQuery<HTMLElement>, horizontalHandler, verticalHandler);
        containerEditool.attr("style", el.attr("style") as string);

        let isResizingHorizontal = false, isResizingVertical = false;
        let startX: number, startY: number, startWidth: number, startHeight: number;

        horizontalHandler.add(verticalHandler).on('mousedown touchstart', function (e: any) {
            isResizingHorizontal = $(this).is('.resize-horizontal');
            isResizingVertical = $(this).is('.resize-vertical');
            $(this).addClass('active');

            startX = e.clientX || e.originalEvent.touches[0].clientX;
            startY = e.clientY || e.originalEvent.touches[0].clientY;
            startWidth = (el?.width() as number);
            startHeight = (el.height() as number);
            e.preventDefault(); // Prevents text selection and touch actions.
        });

        $(document).on('mousemove touchmove', (e: any) => {

            if (!isResizingHorizontal && !isResizingVertical) return;

            let moveX = e.clientX || e.originalEvent.touches[0].clientX;
            let moveY = e.clientY || e.originalEvent.touches[0].clientY;

            let newWidth = Math.round(startWidth + (moveX - startX)); // Minimum size of 100px
            let newHeight = Math.round(startHeight + (moveY - startY)); // Minimum size of 80px

            if (isResizingHorizontal) {
                el.width(newWidth);
                containerEditool.width(newWidth + 20);
            }
            if (isResizingVertical) {
                el.height(newHeight);
                containerEditool.height(newHeight + 10);
            }

            (this.el?.data("instance") as any).attribute = { width: Math.round(el.width() as number), height: Math.round(el.height() as number) };

        }).on('mouseup touchend', function (e) {
            isResizingHorizontal = false;
            isResizingVertical = false;
            $(document).find(".edit-tool").removeClass('active');
        });

        $(document).on("widget:drag:start mousedown touchstart", (e: any) => {

            if (!containerEditool.is(e.target) && containerEditool.has(e.target).length === 0) {

                isResizingHorizontal = false;
                isResizingVertical = false;

                $(this.el as JQuery<HTMLElement>).children().first();

                this.el?.insertBefore(containerEditool);
                containerEditool.remove();

                $(document).off("widget:drag:start mousedown touchstart");

                // console.log(this.el?.data("instance"));
            }

        });
    }



    toggleEditing(idOnEdit: boolean) {

        if (idOnEdit) {
            this.el?.on("click", () => this.showEditTools())
            this.replaceView();
            return;
        }

        this.render();
        this.el?.off("click")
    }



    toolbox(): JQuery<HTMLElement> {

        let toolbox = $(`<div class="widget-toolbox widget-block">
        <div class="toolbox-icon">
            <i class="widget-ic ic-default"></i>
        </div>
        <span class="toolbox-name">${this.title}</span></div>`);

        return toolbox;
    }




    replaceView() {

        let icon = (this.attribute as any).icon || `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ff94fb" class="bi bi-bounding-box" viewBox="0 0 16 16"> <path d="M5 2V0H0v5h2v6H0v5h5v-2h6v2h5v-5h-2V5h2V0h-5v2zm6 1v2h2v6h-2v2H5v-2H3V5h2V3zm1-2h3v3h-3zm3 11v3h-3v-3zM4 15H1v-3h3zM1 4V1h3v3z"/> </svg>`;

        if ((icon as string).startsWith("<svg")) {
            icon = URL.createObjectURL(new Blob([icon], { type: 'image/svg+xml' }));
        }
        this.el?.html(`<div style="color: #ff94fb; display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;"><img src="${icon}"><span style="margin-left: 10px;">${this.title}</span></div>`);
    }




    render(): JQuery<HTMLElement> {

        if (!this.el) {
            this.el = $(`<div class="widget-block"></div>`);
        }

        let attribute = this.attribute as any;
        if (attribute) {

            if (attribute.width) this.el.width(attribute.width);
            if (attribute.height) this.el.height(attribute.height);
            this.el.attr(attribute);
        }


        if (WgetEditing.isEditing) this.replaceView();


        return this.el;
    }

}




export {
    WdgetBlock,
    WdgetBlockType
};