/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */

import "./../css/style.scss";
import { WdgetContainer, WdgetContainerType } from "./WdgetContainer";
import { WdgetEntity, WdgetEntityManager } from "./WdgetEntity";
import { Wdget_EventDragStart, Wdget_EventDragStop, Wdget_EventDraggingMove, Wdget_EventDraggingIn, Wdget_EventDraggingOut, Wdget_EventDrop, Wdget_EventSource, Wdget_EventCoordinate, Wdget_EventMovingData } from "./WdgetEvent";


type WgetOptions = {
    toggler: string;
    data: WdgetContainerType[]
}


class Wdget {

    holder: JQuery<HTMLElement>;
    $toggler: JQuery;
    containers: WdgetContainer[] = [];
    wgetEditing: WgetEditing;
    entityManager: WdgetEntityManager = new WdgetEntityManager(this);


    constructor(holder: string | JQuery<HTMLElement>, options: WgetOptions) {

        if (typeof holder === "string") holder = $(holder);
        if (options) {
            if (options.toggler) {
                this.setToggler($((typeof options.toggler === "string" ? $(options.toggler) : options.toggler)));
            }
            if (options.data) {
                this.setData(options.data);
            }
        }

        this.setContainer(holder);
        this.wgetEditing = new WgetEditing(this);

        setTimeout(() => {
            $(this.holder).trigger("widget:ready");
        }, 400);
        this.render();
    }





    setData(data: WdgetContainerType[]) {

        if (data.length <= 0) this.containers = [];

        for (let x = 0; x < data.length; x++) {

            let blockData: WdgetContainerType = data[x];
            let block = WdgetContainer.fromData(this, blockData);
            this.containers.push(block);
        }

        this.render();
    }

    getData() {

        let data: any = [];

        this.containers.forEach(container => {
            let blocks: any = [];

            container.blocks.forEach(block => {
                blocks.push({
                    name: block.name,
                    title: block.title,
                    content: block.content,
                    attribute: block.attribute
                })
            })
            data.push({
                title: container.title,
                blocks: blocks
            })
        })

        return data;
    }





    setContainer(holder: string | JQuery<HTMLElement>) {

        if (!holder) return;

        this.holder = $(typeof holder === "string" ? $(holder) : holder);
        this.dragHandleDefinition(this.holder);
        this.render();

    }




    dragHandleDefinition(target: JQuery<HTMLElement>) {

        let currentTarget: HTMLElement | null = null;
        target.on("widget:dragging:in", (e, d: Wdget_EventDraggingIn) => {

            if (d.target !== target[0]) return;
            currentTarget = d.target;
            target.addClass("highlight");

        })
        target.on("widget:dragging:out", (e, d: Wdget_EventDraggingOut) => {

            if (!currentTarget && currentTarget !== target[0]) return;
            currentTarget = null;
            target.removeClass("highlight");

        })
        target.on("widget:drop", (e, d: Wdget_EventDrop) => {

            if (d.target !== target[0]) return;
            target.removeClass("highlight");
            this.containers.splice(d.index, 0, new WdgetContainer(this));
            this.render();
        })
    }




    setToggler(toggler: string | JQuery<HTMLElement>) {

        if (!toggler) return;

        this.$toggler = $(typeof toggler === "string" ? $(toggler) : toggler);
        this.$toggler.on("click", () => {
            this.wgetEditing.toggle();
        })
    }




    getHolder() {
        return this.holder
    }




    render() {

        if (!this.holder) return;
        this.holder.html("");
        this.containers.forEach((x) => {
            this.holder.append(x.render());
        })

        if (WgetEditing.isEditing) this.wgetEditing.begins();
    }
}






class WdgetToolbar {


    el: JQuery = $(`<div class="widget-toolbar"></div>`);
    wgetEditing: WgetEditing;




    constructor(wgetEditing: WgetEditing) {
        this.wgetEditing = wgetEditing;
    }




    init() {

        if ($(document.body).find("div.widget-toolbar").length <= 0) {

            this.el.appendTo(document.body);
            setTimeout(() => {
                this.el.addClass("open");
            }, 100)

            this.render();
        }
    }




    destroy() {

        this.el.removeClass("open");
        this.el.remove();
    }




    render() {


        const holder = $(`<div class="toolbar-container"></div>`);
        this.el.html("")
        holder.appendTo(this.el);

        this.wgetEditing.toolboxs.forEach((x) => {
            x.appendTo(holder);
        })
    }




    getBlockManager(): WdgetEntityManager {
        return this.wgetEditing.wget.entityManager;
    }
}



class WgetEditing {


    static isEditing: boolean = false;
    wget: Wdget;
    toolbar: WdgetToolbar = new WdgetToolbar(this);
    onToggleCallback: Function = function (e: boolean) { console.log(e) };
    toolboxs: JQuery<HTMLElement>[] = [];


    constructor(wget: Wdget) {
        this.wget = wget;
    }


    toggle() {

        WgetEditing.isEditing = false;

        if ($(document.body).hasClass("widget-editing")) {

            $(this.wget.holder).trigger("widget:edit:close");
            $(document.body).find("#sidenav").css("display", "unset");
            $(document.body).removeClass("widget-editing");
            this.wget.containers.forEach(e => $(e.el).trigger("widget:edit:close"));
            this.toolbar.destroy();

        } else {

            $(this.wget.holder).trigger("widget:edit:start");
            WgetEditing.isEditing = true;
            $(document.body).find("#sidenav").css("display", "none");
            $(document.body).toggleClass("widget-editing");
            this.begins();

        }

        this.onToggleCallback(WgetEditing.isEditing);
    }




    begins() {

        this.toolboxs = [];
        this.wget.entityManager.getEntities().forEach((x) => {
            let toolbox = x.toolbox();
            this.dragHandle(toolbox, x);
            this.toolboxs.push(toolbox);
        })

        this.wget.containers.forEach(e => $(e.el).trigger("widget:edit:start"));
        this.toolbar.init();
    }




    dragHandle(el: JQuery<HTMLElement>, entity: WdgetEntity) {

        //  var isOnDrag = true;
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        var clone = $(el.clone());

        el.on("mousedown touchstart", (e: any) => {

            let pos_x = (e.pageX || e.originalEvent.touches[0].pageX) + (isMobile ? 40 : 10);
            let pos_y = (e.pageY || e.originalEvent.touches[0].pageY) - (isMobile ? 40 : 10);

            $(document.body).addClass("scrolling-disabled");
            el.addClass("dragging");

            el.trigger("widget:drag:start", new Wdget_EventDragStart(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));
            $(this.wget.holder).trigger("widget:drag:start", new Wdget_EventDragStart(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));
            $(document).trigger("widget:drag:start", new Wdget_EventDragStart(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));

            clone.appendTo("body");
            clone.css({
                "position": "absolute",
                "z-index": 9999,
                "opacity": 0.5,
                "cursor": "move",
                "top": (pos_y - ((clone.height() as number) / 2)) + "px",
                "left": (pos_x - ((clone.width() as number) / 2)) + "px",
                "width": Math.min(Math.round(el.width() as number), 100) + "px",
                "height": Math.min(Math.round(el.height() as number), 100) + "px",
            })

            clone.on("widget:drag:stop", (e: any) => {

                el.trigger("widget:drag:stop", new Wdget_EventDragStop(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));
                $(this.wget.holder).trigger("widget:drag:stop", new Wdget_EventDragStop(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));
                $(document).trigger("widget:drag:stop", new Wdget_EventDragStop(new Wdget_EventSource(el[0], entity), { x: pos_x, y: pos_y }));

                $(document.body).removeClass("scrolling-disabled");
                el.removeClass("dragging");
                clone.remove();
            });

            this.#draggingControl(clone, entity);

            e.stopPropagation();
        })
    }




    #draggingControl(clone: JQuery<HTMLElement>, entity: WdgetEntity) {

        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        var isBlockContainer = entity.constructor.name === "WdgetContainer";
        var underElement: HTMLElement | null = null;

        var targets: WdgetEntity[] | JQuery[] = [this.wget.getHolder()];
        var index = 0;

        if (!isBlockContainer) {
            targets = this.wget.containers;
        }

        $(document).on("mousemove touchmove", (e: any) => {

            let pos_x = (e.pageX || e.originalEvent.touches[0].pageX);
            let pos_y = (e.pageY || e.originalEvent.touches[0].pageY);

            clone.css({
                "top": (pos_y - ((clone.height() as number) / 2)) - (isMobile ? 40 : 10) + "px",
                "left": (pos_x - ((clone.width() as number) / 2)) + (isMobile ? 40 : 10) + "px"
            });

            targets.forEach((instance: object) => {

                let target = isBlockContainer ? $(instance) : $((instance as WdgetContainer).el);

                let containerMarginBorderWidth = ((target.outerWidth(true) as number) - (target.width() as number)) / 2;
                var boxPos = target.offset() as JQuery.Coordinates;
                var boxRight = boxPos.left + (target.width() as number);
                var boxBottom = boxPos.top + (target.height() as number);

                if (pos_x > (boxPos.left - containerMarginBorderWidth)
                    && pos_x < (boxRight + containerMarginBorderWidth)
                    && pos_y > (boxPos.top - containerMarginBorderWidth)
                    && pos_y < (boxBottom + (containerMarginBorderWidth))) {


                    if (underElement != target[0]) {
                        underElement = target[0] as HTMLElement;
                        $(underElement).trigger("widget:dragging:in",
                            new Wdget_EventDraggingIn(new Wdget_EventSource(clone[0], entity), underElement, { x: pos_x, y: pos_y }));
                    }


                    if (underElement === target[0]) {

                        $(underElement).trigger("widget:dragging:move", new Wdget_EventDraggingMove(new Wdget_EventSource(clone[0], entity), underElement, { x: pos_x, y: pos_y }));
                        if (isBlockContainer) {
                            index = this.findPosition(this.wget.containers, this.wget.getHolder(), { x: pos_x, y: pos_y })
                        } else {
                            index = this.findPosition((instance as WdgetContainer).blocks, $(underElement), { x: pos_x, y: pos_y });
                        }
                    }

                } else if (pos_x < boxPos.left || pos_x > boxRight || pos_y < boxPos.top || pos_y > boxBottom) {
                    if (underElement != null && underElement == target[0]) {

                        $(underElement).trigger("widget:dragging:out", new Wdget_EventDraggingOut(new Wdget_EventSource(clone[0], entity), { x: pos_x, y: pos_y }));
                        $(document).find("div.widget-temp").remove();
                        underElement = null;

                    }
                }
            })
        })


        $(document).on("mouseup touchend", (e: any) => {

            if (underElement) {
                $(underElement).trigger("widget:drop", new Wdget_EventDrop(new Wdget_EventSource(clone[0], entity), { x: 0, y: 0 }, index, underElement));
            }

            $(document).find("div.widget-temp").remove();

            $(document).off("mousemove");
            $(document).off("touchmove");
            $(clone).trigger("widget:drag:stop");
            clone.remove();
            underElement = null;
        })
    }




    findPosition(entitys: WdgetEntity[], parent: JQuery<HTMLElement>, coordinate: Wdget_EventCoordinate): number {

        const parentPaddingMarginBorderWidth = 10;
        const mookBox = $(`<div class='widget-block widget-temp'></div>`);

        let index = 0;
        let isMookInsert = false;

        for (let i = 0; i < entitys.length; i++) {

            const entity = entitys[i];
            if (!entity.el) continue;

            const target = $(entity.el);
            const targetPos = target.offset() as JQuery.Coordinates;
            const targetRight = targetPos.left + (target.innerWidth() as number);
            const targetBottom = targetPos.top + (target.innerHeight() as number);
            const marginSize = Math.round(((target.outerWidth(true) as number) - (target.outerWidth() as number)) / 2);

            let palingKiri = (targetPos.left - marginSize - parentPaddingMarginBorderWidth);
            let palingKanan = (targetRight + marginSize + parentPaddingMarginBorderWidth);
            let palingAtas = (targetPos.top - marginSize - parentPaddingMarginBorderWidth);
            let palingBawah = (targetBottom + marginSize + parentPaddingMarginBorderWidth);


            if (coordinate.x > palingKiri && coordinate.x < palingKanan && coordinate.y > palingAtas && coordinate.y < palingBawah) {
                parent.find(".widget-temp").remove();
                if (coordinate.x > (targetPos.left + (target.outerWidth(true) as number) / 2) || coordinate.y > (targetPos.top + (target.outerHeight(true) as number) / 2)) {
                    mookBox.insertAfter(target);
                } else {
                    mookBox.insertBefore(target);
                }
                isMookInsert = true;
                break;
            }
        }

        if (!isMookInsert && parent.find(".widget-temp").length <= 0) {
            parent.append(mookBox);
        }

        parent.find(">div").each((i, el) => {
            if (el.classList.contains("widget-temp")) {
                index = i;
                return false;
            }
        });

        return index;
    }



    onToggle(callable: Function) {
        this.onToggleCallback = callable;
    }
}




export {
    Wdget,
    WgetEditing
}