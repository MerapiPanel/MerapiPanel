/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */

import { WdgetBlock } from "./WdgetBlock";
import { WdgetEntity } from "./WdgetEntity";
import { Wdget_EventDraggingIn, Wdget_EventDraggingMove, Wdget_EventDraggingOut, Wdget_EventDrop } from "./WdgetEvent";
import { Wdget } from "./Widget";


type WdgetContainerType = {
    title: string;
    attribute: object;
    blocks: WdgetBlock[];
}

type BlocksChange = {
    type: string;
    data: object;
}

class WdgetContainer implements WdgetEntity, WdgetContainerType {

    wdget: Wdget;
    isOnEditing: boolean = false;
    el: JQuery<HTMLElement>;
    title: string = "Container";
    attribute: object;
    blocks: WdgetBlock[] = [];

    constructor(wdget: Wdget, arg?: WdgetContainerType) {

        this.wdget = wdget;

        if (arg) {

            this.title = arg.title;
            this.attribute = arg.attribute;

            if (arg.blocks.every((block) => block instanceof WdgetBlock)) {
                this.blocks = arg.blocks;

            } else if (arg.blocks.every((block) => block instanceof Object)) {

                this.initBlockData(arg.blocks);
            }
        }


        // Save a reference to the original splice method
        const originalSplice = Array.prototype.splice;
        const originalPush = Array.prototype.push;

        // Override the splice method for this.blocks
        Object.defineProperty(this.blocks, "splice", {
            value: function (start, deleteCount, ...items) {
                // Perform the actual splice operation using the original method
                const result = originalSplice.apply(this, arguments);
                if (typeof this.onSplice === "function") {
                    // You can customize what you pass to your event/function
                    this.onSplice({
                        start: start,
                        deleteCount: deleteCount,
                        addedItems: items,
                        removedItems: result
                    });
                }
                // Return the array of removed elements (consistent with the default splice behavior)
                return result;
            },
            configurable: false, // prevents further attempts to modify the property definition
            enumerable: false // hides the property from for..in loops
        });

        Object.defineProperty(this.blocks, "push", {
            value: function (...items) {
                // Perform the actual push operation using the original method
                const result = originalPush.apply(this.blocks, items);
                if (typeof this.onPush === "function") {
                    this.onPush({
                        addedItems: items,
                        result: result
                    });
                }
                return result;

            },
            configurable: false,
            enumerable: false
        });

        (() => {
            (this.blocks as any).onSplice = (event: object) => {
                this.#onBlocksChange({
                    type: "splice",
                    data: event
                });
            }
            (this.blocks as any).onPush = (event: object) => {
                this.#onBlocksChange({
                    type: "push",
                    data: event
                });
            }
        })()
    }




    initBlockData(data: Object[]) {

        data.forEach(element => {

            let block = new WdgetBlock(this.wdget, {
                title: element["title"] ?? "",
                name: element["name"] ?? "",
                content: element["content"] ?? "",
                attribute: element["attribute"] ?? {}
            });

            this.blocks.push(block);
        });


    }




    toolbox(): JQuery {
        return $(`<div class="widget-toolbox widget-block-container"><div class="toolbox-icon"><i class="widget-ic ic-default"></i></div><span class="toolbox-name">${this.title}</span></div>`);
    }



    eventEditDefine() {

        let currentTarget: HTMLElement | null = null;
        let target = $(this.el);

        // remove event
        this.el.off("widget:dragging:in");
        this.el.off("widget:dragging:out");
        this.el.off("widget:dragging:move");
        this.el.off("widget:drop");

        target.on("widget:dragging:in", (e, d: Wdget_EventDraggingIn) => {

            if (d.target !== target[0]) return;
            currentTarget = d.target;
            target.addClass("highlight");

        })


        target.on("widget:dragging:out", (e, d: Wdget_EventDraggingOut) => {


            if (!currentTarget && currentTarget !== target[0]) return;

            currentTarget = null;

            this.el.find(".widget-mook").remove();
            target.removeClass("highlight");

        })


        target.on("widget:dragging:move", (e, d: Wdget_EventDraggingMove) => {


            if (d.target !== target[0]) return;


        })


        target.on("widget:drop", (e, d: Wdget_EventDrop) => {

            if (d.target !== target[0]) return;


            $(this.el).removeClass("highlight");

            if (d.source?.entity instanceof WdgetBlock) {

                let title = d.source?.entity.title;
                let name = d.source?.entity.name;
                let content = d.source?.entity.content;
                let attribute = d.source?.entity.attribute;

                this.blocks.splice(d.index, 0, new WdgetBlock(this.wdget, { title, name, content, attribute }));
            }

            this.el.html("");
            this.blocks.forEach((block) => {
                this.el.append(block.render());
            })
        })
    }


    #startEditing() {
        this.isOnEditing = true;
        this.dispatchEdit();

    }

    #stopEditing() {
        this.isOnEditing = false;
        this.dispatchEdit();
    }

    #onBlocksChange(arg: BlocksChange) {
        // console.log(arg)
        setTimeout(() => { // delay to make sure real method is triggered
            this.dispatchEdit();
        }, 200);
    }

    #editTitle(x: number, y: number) {
        let wrapper = $(`<div class="widget-container-title-edit-wrapper"></div>`);
        let input = $(`<input type="text" class="widget-container-title-edit" value="${this.title}"/>`);
        let saveBtn = $(`<button class="btn btn-sm btn-primary !rounded-none"><i class="fa-regular fa-circle-check"></i></button>`);
        wrapper.css({
            position: "absolute",
            top: y + "px",
            left: x + "px",
            zIndex: 1,
            display: "flex",
            alignItems: "center",
            justifyContent: "flex-start",
            backgroundColor: "white",
            borderRadius: "0.3rem",
            overflow: "hidden",
        })
        input.css({
            outline: "none",
            padding: "0.1rem 0.6rem"
        })

        wrapper.append(input, saveBtn);

        this.el.append(wrapper);
        input.trigger("focus");


        input.on("keydown", (e) => {
            if (e.key === "Enter") {
                saveBtn.trigger("click");
            }
        })
        saveBtn.on("click", () => {
            this.title = (input.val() as string);
            wrapper.remove();
            this.render();
        })

        setTimeout(() => {
            $(document).on("click", (e: any) => {
                if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
                    wrapper.remove();
                }
            })
        }, 200)
    }

    dispatchEdit() {

        if (this.isOnEditing) {

            this.el.attr("widget-container-title", (this.title && this.title.length > 0) ? this.title : "No title");

            // add event
            this.blocks.forEach((block) => block.toggleEditing(true, this));
            this.eventEditDefine();


            this.el.on("click", (e) => {


                $(".widget-block-container").removeClass("highlight");
                $(".widget-block-container").find("button[data-widget-remover]").remove();

                if (this.el.is(e.target)) {
                    this.el.toggleClass("highlight");

                    let remover = $(`<button data-widget-remover class="btn btn-sm btn-danger !rounded-full"><i class="fa-regular fa-trash-can"></i></button>`);

                    remover.css({
                        position: "absolute",
                        bottom: " 100%",
                        left: " 100%",
                        padding: "0.6rem",
                        display: "flex",
                        justifyContent: "center",
                        alignItems: "center",
                        alignContent: "center",
                        aspectRatio: 1,
                    })
                    this.el.append(remover)

                    remover.on("click", () => {
                        let index = this.wdget.containers.indexOf(this);
                        this.wdget.containers.splice(index, 1);
                        this.wdget.render();
                    })

                    $(document).on("click", (e: any) => {
                        if (!this.el.is(e.target) && this.el.has(e.target).length === 0) {
                            $(".widget-block-container").removeClass("highlight");
                            $(".widget-block-container").find("button[data-widget-remover]").remove();
                        }
                    })
                }

                let offset = { x: e.offsetX, y: e.offsetY };
                let pseudoAfter = window.getComputedStyle(e.target, '::after');
                let h = parseInt(pseudoAfter.height, 10);
                let w = parseInt(pseudoAfter.width, 10);

                if (offset.y, (offset.y < h && offset.y > -(h * 1.4)), (offset.x < w && offset.x > -(w * 1.4))) {
                    this.#editTitle(parseInt(pseudoAfter.left), parseInt(pseudoAfter.top));
                }
            })
            return;
        }

        $(".widget-block-container").find("button[data-widget-remover]").remove();
        this.el.attr("widget-container-title", this.title);

        // remove event
        this.el.off("widget:dragging:in");
        this.el.off("widget:dragging:out");
        this.el.off("widget:dragging:move");
        this.el.off("widget:drop");
        this.el.off("click");

        this.blocks.forEach((block) => block.toggleEditing(false, this));

    }


    render(): JQuery {

        if (!this.el) {
            this.el = $(`<div class="widget-block-container"></div>`);
        }

        $(this.el).off("widget:edit:start");
        $(this.el).off("widget:edit:close");
        $(this.el).on("widget:edit:start", () => { this.#startEditing() })
        $(this.el).on("widget:edit:close", () => { this.#stopEditing() })


        if (this.el.attr("id") == undefined) {
            this.el.attr("id", Date.now().toString());
        }

        if (this.isOnEditing) {
            this.el.attr("widget-container-title", (this.title && this.title.length > 0) ? this.title : "No title");
        } else {
            this.el.attr("widget-container-title", this.title || "");
        }


        this.el.html("");
        this.blocks.forEach((block) => {
            this.el.append(block.render());
        })


        return this.el;
    }




    static fromData(wdget: Wdget, data: WdgetContainerType | undefined) {
        return new WdgetContainer(wdget, data);
    }

}


export {
    WdgetContainer,
    WdgetContainerType
};