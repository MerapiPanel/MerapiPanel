

// class BlockManager {

import { WdgetEntity } from "./WdgetEntity";

//     stack: BlockStackType = {};

//     constructor() {
//         this.stack = {
//             "box": new Block({
//                 name: "Box",
//                 content: "<div class='widget-box'></div>",
//                 attribute: ["css"]
//             })
//         };
//     }

//     addBlock(name: string, options: { name: string; content: string; attribute: { class: string; }; }) {
//         return this.stack[name.toLowerCase()] = new Block(options);
//     }

//     getBlock(name: string | number) {
//         return this.stack[name];
//     }

//     getBlocks(): BlockType[] {

//         let stack: BlockType[] = [];
//         Object.keys(this.stack).forEach((key) => {
//             stack.push(this.stack[key]);
//         })
//         return stack;
//     }

//     removeBlock(name: string | number) {
//         delete this.stack[name];
//     }
// }



type WdgetBlockType = {
    name: string;
    content: string;
    attribute: object;
}

class WdgetBlock implements WdgetEntity, WdgetBlockType {

    el?: JQuery<HTMLElement> | undefined;
    name: string;
    content: string;
    attribute: object;



    constructor({
        name,
        content,
        attribute
    }: WdgetBlockType) {

        this.name = name;
        this.content = content;
        this.attribute = attribute;
    }


    toolbox(): JQuery<HTMLElement> {

        return $(`<div class="widget-toolbox widget-block"><div class="toolbox-icon"><i class="widget-ic ic-default"></i></div><span class="toolbox-name">${this.name}</span></div>`);
    }
    render(): JQuery<HTMLElement> {

        if (!this.el) {
            this.el = $(`<div class="widget-block"></div>`);
        }

        return $(`<div class="widget-block">${this.content}</div>`);


    }

}




export {
    WdgetBlock,
    WdgetBlockType
};