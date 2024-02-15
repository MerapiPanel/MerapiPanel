
import { data, event } from "jquery";
import { WdgetBlock } from "./WdgetBlock";
import { WdgetEntity } from "./WdgetEntity";
import { DragEvent } from "./Widget";


type WdgetContainerType = {
    title: string;
    attribute: object;
    blocks: WdgetBlock[];
}

class WdgetContainer implements WdgetEntity, WdgetContainerType {


    el: JQuery<HTMLElement>;
    title: string = "Container";
    attribute: object;
    blocks: WdgetBlock[] = [];



    constructor(arg?: WdgetContainerType) {

        if (arg) {

            this.title = arg.title;
            this.attribute = arg.attribute;

            if (arg.blocks.every((block) => block instanceof WdgetBlock)) {
                this.blocks = arg.blocks;

            } else if (arg.blocks.every((block) => block instanceof Object)) {

                this.initBlockData(arg.blocks);
            }
        }

    }




    initBlockData(data: Object[]) {

        data.forEach(element => {
            this.blocks.push(new WdgetBlock({
                name: element["name"] ?? "",
                content: element["content"] ?? "",
                attribute: element["attribute"] ?? {}
            }));
        });


    }




    toolbox(): JQuery {
        return $(`<div class="widget-toolbox widget-block-container"><div class="toolbox-icon"><i class="widget-ic ic-default"></i></div><span class="toolbox-name">${this.title}</span></div>`);
    }




    dragHandleDefinition(target: JQuery<HTMLElement>) {

        let currentTarget: HTMLElement | null = null;
        target.on("drag:in", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;
            currentTarget = d.target;
            console.log("in")
            target.addClass("highlight");

        })
        target.on("drag:out", (e, d: DragEvent) => {

            if (!currentTarget && currentTarget !== target[0]) return;
            currentTarget = null;
            // $("div.widget-block.widget-mook").remove();
            // console.log("out")
            target.removeClass("highlight");

        })
        target.on("drag:drop", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;

            $(this.el).removeClass("highlight");

            this.blocks.push(d.source?.entity as WdgetBlock);

            target.off("drag:in");
            target.off("drag:out");
            target.off("drag:drop");
            target.off("drag:move");

            this.render();

            // $(this.el).append("<div class='widget-block widget-mook'></div>");
            // console.log("drop to : ", this.el);
            // $("div.widget-block.widget-mook").remove();


        })

        target.on("drag:move", (e, d: DragEvent) => {

            if (d.target !== target[0]) return;

            // console.log("move")
            this.dragMove(d);

        })
    }


    dragMove(data: DragEvent) {



        if (this.blocks.length <= 0 && data.target != undefined) {

            // console.clear();
            // console.log(this.blocks, data.target)

            $(data.target).append($("div.widget-block.widget-mook"));

            return;
        }

        this.blocks.forEach((block) => {

            if (block.el == undefined) return;

            let box = $(block.el);
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


    // mookBlockView( ,)



    render(): JQuery {

        if (!this.el) {
            this.el = $(`<div class="widget-block-container"></div>`);
        }

        this.dragHandleDefinition(this.el);

        if (this.el.attr("id") == undefined) {
            this.el.attr("id", Date.now().toString());
        }

        if (this.title) {
            this.el.attr("widget-container-title", this.title);
        }

        this.el.html("");
        this.blocks.forEach((block) => {
            this.el.append(block.render());
        })


        return this.el;
    }




    static fromData(data: WdgetContainerType | undefined) {
        return new WdgetContainer(data);
    }

}


export {
    WdgetContainer,
    WdgetContainerType
};