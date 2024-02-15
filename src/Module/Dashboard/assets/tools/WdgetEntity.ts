import { WdgetBlock } from "./WdgetBlock";
import { WdgetContainer } from "./WdgetContainer";

type WdgetEntity = {
    el?: JQuery;
    
    toolbox(): JQuery;
    render(): JQuery;
}

type WdgetEntityStack = {
    [type in string]: WdgetEntity
}

class WdgetEntityManager {

    stack: WdgetEntityStack = {};

    constructor() {
        this.stack = { "box": new WdgetContainer() };
    }

    addEntity(name: string, entity : WdgetEntity) {

        if(entity instanceof WdgetBlock) {
            this.stack[name.toLowerCase()] = entity;
        } else if(typeof entity === "object") {
            this.stack[name.toLowerCase()] = new WdgetBlock({
                name: name,
                content: entity["content"],
                attribute: entity["attribute"]
            });
        }
    }

    getEntity(name: string | number) {
        return this.stack[name];
    }

    getEntities(): WdgetEntity[] {

        let stack: WdgetEntity[] = [];
        Object.keys(this.stack).forEach((key) => {
            stack.push(this.stack[key]);
        })
        return stack;
    }

    removeBlock(name: string) {
        delete this.stack[name];
    }
}

export { WdgetEntity, WdgetEntityManager };