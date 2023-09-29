import $, { data } from 'jquery';
import Merapi from '../../../../../base/assets/merapi';
import { random, tail, trim, trimEnd, uniqueId } from 'lodash';

export default (editor, opts = {}) => {

    var comStack = [];
    const bm = editor.BlockManager;

    editor.Commands.add('get-html-twig', {
        run: function (editor, sender, opts = {}) {

            let twigStack = [];

            comStack.forEach(function (component) {

                let traits = component.model.attributes.traits;
                let params = traits.map(trait => {
                    if (trait.name) {
                        return `"${trait.name}":"${component.attr[trait.name] || ''}"`;
                    } else {
                        return `"${trait.attributes.name}":"${component.attr[trait.attributes.name] || ''}"`;
                    }
                }).join(",");

                let twig = `{{ guest.${component.model.attributes.type}({${params}}) }}`;

                twigStack.push({
                    id: component.attr.id,
                    twig: twig
                });
            });

            comStack = [];

            editor.store();
            let doc = new DOMParser().parseFromString(editor.getHtml(), "text/html");

            twigStack.forEach(function (twig) {

                let div = doc.getElementById(twig.id);
                let parent = div.parentNode;
                div.parentNode.insertBefore(doc.createTextNode(twig.twig), div);
                parent.removeChild(div);
            })

            let htmlstr = doc.body.outerHTML;

            if (opts.callback) {
                opts.callback(htmlstr);
            } else {
                console.log(htmlstr);
            }

        }
    });

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

    const endpoint = trimEnd(decodeURIComponent(Merapi.getCookie("fm-adm-pth")), "/") + "/view-engine/components";

    const createType = (editor, args = {}) => {


        return {

            isComponent: el => el.classList?.contains(args.id),
            model: {
                default: {
                    name: args.id,
                    content: "",
                    droppable: false,
                },
                init() {

                    // Listen to any attribute change
                    this.on('change:attributes', this.handleAttrChange);

                },

                handleAttrChange() {

                    let _endpoint = endpoint + "/static/" + args.id;

                    let params = {};
                    this.getTraits().map(trait => {

                        let name = trait.getName();
                        let value = trait.getValue();
                        params[name] = value;

                        return null;
                    });

                    $.post(_endpoint, params).then(res => {

                        let obj = Object.assign({ data: { output: "" } }, res);

                        console.log(obj);

                        this.set("content", obj.data.output);
                    })

                    console.log(this.getAttributes())
                    console.log('update')
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

                    const typeFind = bm.get(args.id);
                    this.traits = typeFind.attributes.content.traits;
                    this.model.attributes.traits = this.traits;

                    if (!this.attr.id) {
                        this.attr.id = this.generateRandomStringByTime();
                    }

                    editor.store();

                    editor.on("run:get-html-twig:before", () => {
                        comStack.push(this);
                    })
                },
            }
        }
    }

    const createBlock = (editor, args = {}) => {

        return {
            label: args.model.label,
            media: args.model.media,
            content: {
                type: args.id,
                content: args.model.content,
                traits: (() => {
                    const traits = [];
                    for (let i in args.params) {
                        traits.push(createTrait(args.params[i]));
                    }

                    return traits;
                })()

            },
            category: args.category
        }
    }




    $.get(endpoint).then(res => {

        const groupComponent = res.data ?? [];
        for (let i = 0; i < groupComponent.length; i++) {

            const modComponent = groupComponent[i];
            const category = modComponent.name;

            for (let x in modComponent.components) {

                const component = Object.assign({
                    id: null,
                    model: {},
                    params: []
                }, modComponent.components[x]);

                editor.Components.addType(component.id, createType(editor, component));
                component.category = category;
                bm.add(component.id, createBlock(editor, component));
            }
        }
    })
}