class TypeManager {

    #stack = {};

    constructor() {
        this.#stack = {
            "box": new Type({
                name: "Box",
                content: "<div class='widget-box'></div>",
                attributes: ["css"]
            })
        };
    }

    addType(name, options) {
        return this.#stack[name] = new Type(options);
    }

    getType(name) {
        return this.#stack[name];
    }

    getTypes() {
        return this.#stack;
    }

    removeType(name) {
        delete this.#stack[name];
    }

}

class Type {
    name;
    content;
    attributes;

    constructor(options) {
        this.name = options.name;
        this.content = options.content;
        this.attributes = options.attributes;
    }
}


export {
    TypeManager,
    Type
}