import WdgetEntity from "./WdgetBlock";
import { Block } from "./WidgetBlocks";


type BoxType = {
    blocks: Block[];
    el: JQuery<HTMLElement> | undefined;
    title: string | undefined;
    attributes: object | undefined;
}




class WdgetBox implements WdgetEntity {

    el: JQuery<HTMLElement>;

    blocks: Block[] = [];
    title: string | undefined;
    attributes: object | undefined;

    constructor(arg: BoxType) {

        if (!arg) {
            throw new Error("No argument provided");
        }
        this.title = arg.title;
        this.attributes = arg.attributes;
        this.el = arg.el ? $(arg.el) : $(`<div class="widget-box"></div>`);

        if (arg.blocks.every((block) => block instanceof Block)) {
            this.blocks = arg.blocks;
        } else if (arg.blocks.every((block) => block instanceof Object)) {
            this.initBlockData(arg.blocks);
        }
    }


    toolbox(): JQuery<HTMLElement> {
        let icon = "<i class='widget-ic ic-default'></i>";
        let el = $(`<div class='widget-box'><div class='block-icon'>${icon}</div><span class='block-name'>${this.title}</span></div>`);
        return el;
    }


    initBlockData(data: Object[]) {

        data.forEach(element => {
            this.blocks.push(new Block({
                name: element["name"],
                content: element["content"],
                attribute: element["attribute"]
            }));
        });
    }




    render(): JQuery<HTMLElement> {

        if (!this.el) {
            this.el = $(`<div class="widget-box"></div>`);
        }
        if (this.title) {
            this.el.attr("widget-box-title", this.title);
        }

        this.el.html("");
        this.blocks.forEach((block) => {
            this.el.append(block.render());
        })

        return this.el;
    }


    static fromData(data: BoxType): WdgetBox {

        return new WdgetBox(data);
    }
}






type WdgetBoxType = {
    title: string,
    blocks: BlockType[],
}

class WdgetBox implements BlockType {

    el: JQuery<HTMLElement>;

    blocks: BlockType[] = [];
    title: string | undefined;
    attributes: object | undefined;

    constructor(arg: BoxType) {

        if (!arg) {
            throw new Error("No argument provided");
        }
        this.title = arg.title;
        this.attributes = arg.attributes;
        this.el = arg.el ? $(arg.el) : $(`<div class="widget-box"></div>`);

        if (arg.blocks.every((block) => block instanceof Block)) {
            this.blocks = arg.blocks;
        } else if (arg.blocks.every((block) => block instanceof Object)) {
            this.initBlockData(arg.blocks);
        }
    }


    toolbox(): JQuery<HTMLElement> {
        let icon = "<i class='widget-ic ic-default'></i>";
        let el = $(`<div class='widget-box'><div class='block-icon'>${icon}</div><span class='block-name'>${this.title}</span></div>`);
        return el;
    }


    initBlockData(data: Object[]) {

        data.forEach(element => {
            this.blocks.push(new Block({
                name: element["name"],
                content: element["content"],
                attribute: element["attribute"]
            }));
        });
    }




    render(): JQuery<HTMLElement> {

        if (!this.el) {
            this.el = $(`<div class="widget-box"></div>`);
        }
        if (this.title) {
            this.el.attr("widget-box-title", this.title);
        }

        this.el.html("");
        this.blocks.forEach((block) => {
            this.el.append(block.render());
        })

        return this.el;
    }


    static fromData(data: BoxType): WdgetBox {

        return new WdgetBox(data);
    }
}







export {
    WdgetBox,
    BoxType
}