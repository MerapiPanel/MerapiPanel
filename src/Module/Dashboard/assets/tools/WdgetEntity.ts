/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */

import { WdgetBlock } from "./WdgetBlock";
import { WdgetContainer } from "./WdgetContainer";
import { Wdget } from "./Widget";



type WdgetEntity = {
    el?: JQuery;
    
    toolbox(): JQuery;
    render(): JQuery;
}



type WdgetEntityStack = {
    [type in string]: WdgetEntity
}




class WdgetEntityManager {

    
    wdget: Wdget;
    stack: WdgetEntityStack = {};



    /**
     * Constructor for initializing the WdgetEntityManager.
     *
     * @param {Wdget} wdget - the Wdget to be initialized
     */
    constructor(wdget: Wdget) {
        this.wdget = wdget;
        this.stack = { "box": new WdgetContainer(this.wdget) };
    }




    /**
     * Add an entity to the stack based on its type.
     *
     * @param {string} name - the name of the entity
     * @param {WdgetEntity} entity - the entity to be added
     */
    addEntity(name: string, entity : WdgetEntity) {

        if(entity instanceof WdgetBlock) {
            this.stack[name.toLowerCase()] = entity;
        } else if(typeof entity === "object") {
            this.stack[name.toLowerCase()] = new WdgetBlock(this.wdget, {
                name: entity["name"],
                title: entity["title"],
                content: entity["content"],
                attribute: entity["attribute"]
            });
        }
    }



    /**
     * Retrieves an entity from the stack by its name.
     *
     * @param {string | number} name - the name of the entity to retrieve
     * @return {any} the entity from the stack with the given name
     */
    getEntity(name: string | number) {
        return this.stack[name];
    }



    /**
     * Retrieve the list of WdgetEntity objects.
     *
     * @return {WdgetEntity[]} the list of WdgetEntity objects
     */
    getEntities(): WdgetEntity[] {

        let stack: WdgetEntity[] = [];
        Object.keys(this.stack).forEach((key) => {
            stack.push(this.stack[key]);
        })
        return stack;
    }



    
    /**
     * Removes a block from the stack.
     *
     * @param {string} name - the name of the block to be removed
     * @return {void} 
     */
    removeBlock(name: string) {
        delete this.stack[name];
    }
}

export { WdgetEntity, WdgetEntityManager };