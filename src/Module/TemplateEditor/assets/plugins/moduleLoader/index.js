export default (editor, opts = {}) => {

    Object.assign({
        endpoint: {
            fetch: null
        },
        token: null
    }, opts);

    if (!opts.endpoint?.fetch) { merapi.toast("Can't initialize Module Loader without endpoint provided", 5, "text-danger"); return };
    if (!opts.token) { merapi.toast("Can't initialize Module Loader without token provided", 5, "text-danger"); return; }


    var comStack = [];
    const bm = editor.BlockManager;
    const endpoint = opts.endpoint;
    const componentIds = [];




    // editor.Commands.add('get-html-twig', {
    //     run: function (editor, sender, opts = {}) {

    //         let twigStack = [];

    //         comStack.forEach(function (component) {

    //             let attr = component.model.getAttributes();
    //             let traits = component.model.getTraits();

    //             let params = traits.map(trait => {

    //                 return `"${trait.getName()}":"${trait.getValue()}"`;

    //             }).join(",");


    //             let twig = `{{ guest.${component.model.attributes.type}({${params}}) | raw }}`;

    //             twigStack.push({
    //                 id: attr.id,
    //                 twig: twig
    //             });
    //         });

    //         comStack = [];

    //         editor.store();
    //         let doc = new DOMParser().parseFromString(editor.getHtml(), "text/html");

    //         twigStack.forEach(function (twig) {

    //             let div = doc.getElementById(twig.id);
    //             if (!div) return;
    //             let parent = div.parentNode;
    //             div.parentNode.insertBefore(doc.createTextNode(twig.twig), div);
    //             parent.removeChild(div);
    //         })

    //         let htmlstr = doc.body.outerHTML;

    //         if (opts.callback) {
    //             opts.callback(htmlstr);
    //         } else {
    //             console.log(htmlstr);
    //         }

    //     }
    // });









    const createTrait = (args = {}) => {

        const typeList = {
            string: {
                type: 'text',
                default: args.default
            },
            number: {
                type: 'number',
                default: args.default
            },
            object: {
                type: 'select',
                options: args.default
            }
        }

        return Object.assign({}, typeList[typeof args.default], { name: args.name });
    }


















    const createType = (comp = {}) => {


        return {

            isComponent: (el) => {

                console.log(el, el.tagName, String(comp.id).toUpperCase());
                return el.tagName == String(comp.id).toUpperCase();
            },
            model: {
                default: {
                    name: comp.id,
                    content: "",
                    droppable: false,
                    styleable: false
                },
                init() {


                    console.log(this);
                    // Listen to any attribute change
                    this.on('change:attributes', this.handleAttrChange);

                },

                handleAttrChange() {

                    let _endpoint = endpoint.fetch + "/render/" + encodeURIComponent(comp.id) + "?m-token=" + opts.token;

                    // console.log(this);

                    let params = {};
                    this.getTraits().map(trait => {

                        let name = trait.getName();
                        let value = trait.getValue();
                        params[name] = value;

                        return null;
                    });

                    $.post(_endpoint, params).then(res => {

                        let obj = Object.assign({ data: { output: "" } }, res);
                        // console.log(obj);
                       // this.view.el.innerHTML = obj.data.output;
                        this.set("content", obj.data.output);
                    })
                }
            },

            view: {

                generateRandomStringByTime() {
                    var timestamp = new Date().getTime();
                    var randomString = timestamp.toString();
                    return randomString;
                },

                traits: [],
                init() {

                    const typeFind = bm.get(comp.id);
                    this.traits = typeFind.attributes.content.traits;
                    this.model.attributes.traits = this.traits;

                    if (!this.attr.id) {
                        this.attr.id = this.generateRandomStringByTime();
                    }

                    this.model.set("droppable", false);
                    this.model.set("styleable", false);
                    this.model.handleAttrChange();


                    // console.log(this)


                    editor.store();

                    editor.on("run:get-html-twig:before", () => {
                        comStack.push(this);
                    })

                    editor.on("run:open-save-modal:before", () => {
                        this.model.attributes.content = "";

                        // console.log(this.el);

                        $("hallo world").insertBefore($(this.el));

                        parent.insertBefore

                    })

                    editor.on("run:open-code-editor:before", () => {

                        this.model.attributes.content = "";
                        // if (this.el.tagName != String(args.id).toUpperCase()) {
                        //     this.model.attributes.attributes['data-gjs-type'] = args.id;
                        // }
                        return this;
                    })
                },
            }
        }
    }








    const createBlock = (comp = {}) => {

        return {
            label: comp.model.label,
            media: comp.model.media,
            content: {
                droppable: false,
                type: "component",
                content: "",
                traits: (() => {
                    const traits = [];
                    for (let i in comp.params) {
                        traits.push(createTrait(comp.params[i]));
                    }

                    return traits;
                })()

            },
            category: comp.category
        }
    }



    editor.Commands.add('loaded-components', {
        run: function (editor, sender, args) {
            console.log("Components loaded successful");
        }
    })



    $.get(endpoint.fetch).then(res => {

        const groupComponent = res.data ?? [];

        for (let i = 0; i < groupComponent.length; i++) {

            const modComponent = groupComponent[i];
            const category = modComponent.name;

            for (let x in modComponent.components) {

                const component = modComponent.components[x];
                Object.assign({
                    id: null,
                    model: {},
                    params: []
                }, component);

                editor.Components.addType(component.id, createType(component));
                component.category = category;
                bm.add(component.id, createBlock(component));
            }
        }


        setTimeout(() => {
            editor.runCommand('loaded-components');
        }, 250);

    })
}