import WdgetEntity from "./WdgetBlock"

type BlockStackType = {
    [key: string]: BlockType
}


type BlockType = {
    name: string
    content: string
    attribute: object
    icon?: string
    render(): JQuery
    toolbox(): JQuery
}








class Block implements WdgetEntity {

    el: JQuery<HTMLElement>;

    name: string;
    content: string;
    attribute: object;
    icon?: string | undefined

    constructor(options: { name: any; content: any; attribute: any; }) {
        this.name = options.name;
        this.content = options.content;
        this.attribute = options.attribute;
    }




    toolbox(): JQuery<HTMLElement> {
        let icon = "<i class='widget-ic ic-default'></i>";
        let el = $(`<div class='widget-block'><div class='block-icon'>${icon}</div><span class='block-name'>${this.name}</span></div>`);
        return el;
    }


    render(): JQuery {

        return $(this.content);
    }



}


export {
    Block,
    BlockType
}