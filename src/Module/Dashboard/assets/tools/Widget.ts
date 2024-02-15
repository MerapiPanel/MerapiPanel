
import "./../css/style.scss";

import { WdgetBlock, WdgetBlockType } from "./WdgetBlock";
import { WdgetContainer, WdgetContainerType } from "./WdgetContainer";
import { WdgetEntity, WdgetEntityManager } from "./WdgetEntity";


type WgetOptions = {
    toggler: string;
    data: WdgetContainerType[]
}

type DragEventSource = {
    el: HTMLElement | null;
    entity: WdgetEntity | null
}
class DragEvent {

    source: DragEventSource | null;
    target: HTMLElement | null;
    coordinate: {
        x: number,
        y: number
    };

    constructor(source: DragEventSource | null, target: HTMLElement | null, coordinate: {
        x: number,
        y: number
    }) {
        this.source = source;
        this.target = target;
        this.coordinate = coordinate
    }
}



class Wdget {

    #dragEvent: DragEvent;
    holder: JQuery<HTMLElement>;
    $toggler: JQuery;
    containers: WdgetContainer[] = [];
    wgetEditing: WgetEditing;
    entityManager: WdgetEntityManager = new WdgetEntityManager();


    constructor(holder: string | JQuery<HTMLElement>, options: WgetOptions) {

        if (typeof holder === "string") holder = $(holder);
        if (options) {
            if (options.toggler) {
                this.setToggler($((typeof options.toggler === "string" ? $(options.toggler) : options.toggler)));
            }
            if (options.data) {
                this.initialWithData(options.data);
            }
        }

        this.setContainer(holder);
        this.wgetEditing = new WgetEditing(this);

        this.render();

    }




    initialWithData(data: WdgetContainerType[]) {


        for (let x = 0; x < data.length; x++) {

            let blockData: WdgetContainerType = data[x];
            let block = WdgetContainer.fromData(blockData);
            this.containers.push(block);
        }

        this.render();
    }




    setContainer(holder: string | JQuery<HTMLElement>) {

        if (!holder) return;

        this.holder = $(typeof holder === "string" ? $(holder) : holder);
        this.dragHandleDefinition(this.holder);
        this.render();

    }

    dragHandleDefinition(target: JQuery<HTMLElement>) {

        let currentTarget: HTMLElement | null = null;
        target.on("drag:in", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;
            currentTarget = d.target;
            target.addClass("highlight");

        })
        target.on("drag:out", (e, d: DragEvent) => {

            if (!currentTarget && currentTarget !== target[0]) return;
            currentTarget = null;
            $("div.widget-block-container.widget-mook").remove();
            target.removeClass("highlight");

        })
        target.on("drag:drop", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;
            target.removeClass("highlight");

            $("div.widget-block-container.widget-mook").remove();

        })

        target.on("drag:move", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;
            this.onDragging(d);

        })
    }

    onDrop(data: DragEvent) {

    }



    onDragging(data: DragEvent) {

        this.containers.forEach((container) => {

            let box = $(container.el);
            var boxPos = box.offset() as JQuery.Coordinates;
            var boxRight = boxPos.left + (box.width() as number);
            var boxBottom = boxPos.top + (box.height() as number);

            if (data.coordinate.x > boxPos.left && data.coordinate.x < boxRight && data.coordinate.y > boxPos.top && data.coordinate.y < boxBottom) {


                $("div.widget-block-container.widget-mook").remove();
                const mookBox = $(`<div class='widget-block-container widget-mook'></div>`);

                let insertPosition = (data.coordinate.y > (boxPos.top + (box.height() as number / 2))) ? 1 : 0;

                if (insertPosition == 1) {
                    // insert after
                    mookBox.insertAfter(box);
                } else if (insertPosition == 0) {
                    // insert before
                    mookBox.insertBefore(box);
                }

                return;

            } else if (data.coordinate.x > boxPos.left && data.coordinate.x < boxRight && data.coordinate.y > boxPos.top && data.coordinate.y < boxBottom) {
                $("div.widget-block-container.widget-mook").remove();
            }

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
    }
}






class WdgetToolbar {


    el: JQuery = $(`<div class="widget-toolbar"></div>`);
    wgetEditing: WgetEditing;




    constructor(wgetEditing: WgetEditing) {
        this.wgetEditing = wgetEditing;
    }




    init() {

        this.el.appendTo(document.body);
        setTimeout(() => {
            this.el.addClass("open");
        }, 100)

        this.render();
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


    wget: Wdget;
    toolbar: WdgetToolbar = new WdgetToolbar(this);
    onToggleCallback: Function = function (e: boolean) { console.log(e) };

    toolboxs: JQuery<HTMLElement>[] = [];
    //boxStack: WdgetBox[] = [];



    constructor(wget: Wdget) {
        this.wget = wget;
    }


    toggle() {

        let isOpen = false;

        if ($(document.body).hasClass("widget-editing")) {

            $(document.body).find("#sidenav").css("display", "unset");
            $(document.body).removeClass("widget-editing");
            this.toolbar.destroy();

        } else {

            isOpen = true;
            $(document.body).find("#sidenav").css("display", "none");
            $(document.body).toggleClass("widget-editing");
            this.begins();

        }

        this.onToggleCallback(isOpen);

    }




    begins() {

        this.toolboxs = [];
        this.wget.entityManager.getEntities().forEach((x) => {

            let toolbox = x.toolbox();
            this.dragHandle(toolbox, x);
            this.toolboxs.push(toolbox);
        })

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

            clone.on("drag:stop", (e: any) => {
                el.trigger("drag:stop", e.data);
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
        var dragEvent: DragEvent;

        $(document).on("mousemove touchmove", (e: any) => {

            let pos_x = (e.pageX || e.originalEvent.touches[0].pageX);
            let pos_y = (e.pageY || e.originalEvent.touches[0].pageY);

            dragEvent = new DragEvent({
                el: clone[0],
                entity: entity
            }, underElement, { x: pos_x, y: pos_y })

            clone.css({
                "top": (pos_y - ((clone.height() as number) / 2)) - (isMobile ? 40 : 10) + "px",
                "left": (pos_x - ((clone.width() as number) / 2)) + (isMobile ? 40 : 10) + "px"
            });

            if (isBlockContainer) {

                let wdgetHolder = this.wget.getHolder();
                let pos = wdgetHolder.offset() as JQuery.Coordinates;
                let posRight = pos.left + (wdgetHolder.width() as number);
                let posBottom = pos.top + (wdgetHolder.height() as number);

                if (pos_x > pos.left && pos_x < posRight && pos_y > pos.top && pos_y < posBottom) {

                    if (!underElement && underElement != wdgetHolder[0]) {

                        underElement = wdgetHolder[0];
                        dragEvent = new DragEvent({
                            el: clone[0],
                            entity: entity
                        }, underElement, { x: pos_x, y: pos_y })

                        $(underElement).trigger("drag:in", dragEvent);
                    }

                    if (underElement === wdgetHolder[0]) {
                        $(underElement).trigger("drag:move", dragEvent);
                    }

                    // find where mookBlock is placed
                    // this.wget.containers.forEach((boxBlock) => {

                    //     let box = $(boxBlock.el);
                    //     var boxPos = box.offset() as JQuery.Coordinates;
                    //     var boxRight = boxPos.left + (box.width() as number);
                    //     var boxBottom = boxPos.top + (box.height() as number);

                    //     if (pos_x > boxPos.left && pos_x < boxRight && pos_y > boxPos.top && pos_y < boxBottom) {


                    //         $("div.widget-block-container.widget-mook").remove();
                    //         const mookBox = $(`<div class='widget-block-container widget-mook'></div>`);

                    //         let insertPosition = (pos_y > (boxPos.top + (box.height() as number / 2))) ? 1 : 0;

                    //         console.log(insertPosition);
                    //         if (insertPosition == 1) {
                    //             // insert after
                    //             mookBox.insertAfter(box);
                    //         } else if (insertPosition == 0) {
                    //             // insert before
                    //             mookBox.insertBefore(box);
                    //         }

                    //     }
                    // })

                } else if (pos_x < pos.left || pos_x > posRight || pos_y < pos.top || pos_y > posBottom) {
                    if (underElement != null && underElement == wdgetHolder[0]) {

                        $(underElement).trigger("drag:out", new DragEvent({
                            el: clone[0],
                            entity: entity
                        }, null, { x: pos_x, y: pos_y }));
                        underElement = null;
                        dragEvent = new DragEvent({
                            el: clone[0],
                            entity: entity
                        }, underElement, { x: pos_x, y: pos_y })

                    }

                    underElement = null;
                }

            } else {

                this.wget.containers.forEach((boxBlock) => {

                    let block = $(boxBlock.el);
                    var boxPos = block.offset() as JQuery.Coordinates;
                    var boxRight = boxPos.left + (block.width() as number);
                    var boxBottom = boxPos.top + (block.height() as number);

                    if (pos_x > boxPos.left && pos_x < boxRight && pos_y > boxPos.top && pos_y < boxBottom) {


                        if (underElement != block[0]) {

                            underElement = block[0];
                            dragEvent = new DragEvent({
                                el: clone[0],
                                entity: entity
                            }, underElement, { x: pos_x, y: pos_y })

                            $(underElement).trigger("drag:in", dragEvent);
                        }


                        if (underElement === block[0]) {
                            $(underElement).trigger("drag:move", dragEvent);
                        }

                    } else if (pos_x < boxPos.left || pos_x > boxRight || pos_y < boxPos.top || pos_y > boxBottom) {
                        if (underElement != null && underElement == block[0]) {

                            $(underElement).trigger("drag:out", new DragEvent({
                                el: clone[0],
                                entity: entity
                            }, null, { x: pos_x, y: pos_y }));
                            underElement = null;
                            dragEvent = new DragEvent({
                                el: clone[0],
                                entity: entity
                            }, underElement, { x: pos_x, y: pos_y })
                        }
                    }
                })
            }
        })


        $(document).on("mouseup touchend", (e: any) => {

            if (underElement) {
                $(underElement).trigger("drag:drop", dragEvent);
            }

            $(document).off("mousemove");
            $(document).off("touchmove");
            $(clone).trigger("drag:stop");
            clone.remove();
            underElement = null;
        })
    }


    mookBlock(element, data) {

    }


    scanBox() {

        let finder = this.wget.holder.find(">div");

        // finder.each((x) => {
        //     let elbox: JQuery = $(finder[x]);
        //     let name = elbox[0].tagName;
        //     let content = elbox[0].innerHTML;
        //     let attributes = [];
        //     this.boxStack.push(new WdgetBox({
        //         title: name,
        //         attributes: attributes
        //     }));
        // })
    }




    onToggle(callable: Function) {
        this.onToggleCallback = callable;
    }
}




export {
    Wdget,
    DragEvent,
    DragEventSource
}