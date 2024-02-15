import { WdgetEntity } from "./WdgetEntity";

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
            this.el = $(`<div class="widget-block">${this.content}</div>`);
        }

        return this.el;
    }

}




export {
    WdgetBlock,
    WdgetBlockType
};