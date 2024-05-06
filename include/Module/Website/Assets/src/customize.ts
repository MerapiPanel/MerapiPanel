import { AddComponentTypeOptions, Component, Editor, EditorConfig } from "grapesjs";
import { __MP } from "../../../../Buildin/src/main";
import * as Pages from "./plugins/Pages";
import { color } from "@codemirror/theme-one-dark";

const editor: {
    config: EditorConfig,
    fetchBlockURL: string,
    [key: string]: any
} = (window as any).editor;

interface Customize extends __MP {
    website: {
        saveURL: string
        pages: any[]
        testVariable?: Function,
        deletePage?: Function
    }
}
const __: Customize = (window as any).__;


editor.callback = function () {

    const gjsEditor: Editor = this.editor;
    const page = gjsEditor.runCommand("pages:save");
    const formData = new FormData();

    Object.keys(page).forEach((key) => {
        const value = page[key];
        if (typeof value == "object" && !Array.isArray(value)) {
            formData.append(key, JSON.stringify(value));
        } else if (Array.isArray(value)) {
            for (let i = 0; i < value.length; i++) {
                let item = value[i];
                if (typeof item == "object" || Array.isArray(item)) {
                    Object.keys(item).forEach((k) => {
                        formData.append(`${key}[${i}][${k}]`, item[k]);
                    });
                } else {
                    formData.append(`${key}[${i}]`, item);
                }
            }
        } else {
            formData.append(key, page[key]);
        }
    });

    __.http.post(__.website.saveURL, formData)
        .then((result) => {

            if (result.code == 200) {
                this.resolve(result.message || "Page saved", 5, 'text-success');
                const find = __.website.pages.find((x) => x.route == (result.data as any).route);
                if (find) {
                    Object.keys(result.data).forEach((key) => {
                        find[key] = result.data[key];
                    });
                }
            } else {
                this.reject(result.message || "Failed to save page", 5, 'text-danger');
            }
        }).catch((error) => {
            this.reject(error || "Failed to save page", 5, 'text-danger');
        });

}
editor.onReady = function (editor: Editor) {

    const { BlockManager, Components, Panels } = editor;

    Components.addType("website-twig-fragment", {
        extend: "group",
        model: {
            defaults: {
                droppable: true,
                draggable: false,
                editable: false,
                removable: false,
                copyable: false,
                moveable: false,
                components: []
            }
        }
    });

    Components.addType("website-twig-vars", {
        extend: "text",
        model: {
            defaults: {
                droppable: false,
                draggable: false,
                editable: true,
                removable: false,
                copyable: false,
                moveable: false,
                components: [],
                attributes: {
                    placeholder: "Enter vars",
                    class: "d-inline-block",
                }
            }
        }
    });

    Components.addType("website-twig-if", {
        model: {
            defaults: {
                droppable: "[data-gjs-type=website-twig-else],[data-gjs-type=website-twig-for]",
                stylable: false,
                attributes: {
                    condition: "condition"
                },
                components: [
                    {
                        type: "website-twig-fragment"
                    },
                    {
                        type: "website-twig-else",
                    }
                ]
            },
            init() {
                let componentLen = this.get("components").length;
                this.on("change", () => {
                    if (this.get("components").length != componentLen) {

                        componentLen = this.get("components").length;
                        const components = this.get("components");
                        const typeToMove = 'website-twig-else';

                        // Filter components by type
                        const filteredComponents = components.filter(component => component.get('type') === typeToMove);
                        // Remove filtered components from the original array
                        const remainingComponents = components.filter(component => component.get('type') !== typeToMove);
                        // Concatenate the remaining components and the filtered components to move
                        const reorderedComponents = remainingComponents.concat(filteredComponents);

                        this.components().remove(this.components().filter(() => true));
                        this.components().add(reorderedComponents);

                        this.view?.bindTwig();
                    }
                })
            }

        },
        view: {
            bindTwig: function () {

                const condition = this.model.get('attributes').condition || "condition";
                const el_condition = $(`<span placeholder="Enter condition" contenteditable="true">${condition}</span>`)
                    .on("keydown", (e) => {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                        }
                    })
                    .on("input", (e) => {
                        const attributes = this.model.get('attributes');
                        attributes.condition = $(e.target).text();
                        this.model.set('attributes', attributes);
                    })
                    .css({
                        display: "inline-block",
                        minWidth: "50px",
                        fontFamily: "monospace",
                        color: "#0090e3",
                        cursor: "text",
                        outline: "none",
                        border: "none",
                        background: "rgba(0, 0, 0, 0.05)",
                        padding: "0px 5px",
                        borderRadius: "5px",
                        fontSize: "1rem",
                        userSelect: "none",
                        whiteSpace: "nowrap",
                    });


                this.$el.find(".twig").remove();
                this.$el.prepend(
                    $("<div class=\"twig d-inline-block\">")
                        .css({
                            display: "inline-block",
                            minWidth: "50px",
                            fontFamily: "monospace",
                            fontSize: '1.1em',
                            color: "#b942f5",
                        })
                        .append(
                            `<span> {% </span>`,
                            " if ",
                            el_condition,
                            `<span> %} </span>`
                        )
                );
                this.$el.append(
                    $(`<span class="twig">{% endif %}</span>`)
                        .css({
                            display: "inline-block",
                            minWidth: "50px",
                            fontFamily: "monospace",
                            fontSize: '1.1em',
                            color: "#b942f5",
                        })
                )

            },

            init: function () {
                setTimeout(() => {
                    this.bindTwig();
                }, 400)
            }
        }
    });

    Components.addType("website-twig-for", {
        model: {
            defaults: {
                droppable: "[data-gjs-type=website-twig-if],[data-gjs-type=website-twig-else]",
                components: [
                    {
                        type: "website-twig-fragment"
                    },
                ],
                attributes: {
                    item: "item",
                    arrr: "array"
                }
            },
            init() {
                let componentLen = this.get("components").length;
                this.on("change", () => {
                    if (this.get("components").length != componentLen) {

                        componentLen = this.get("components").length;
                        const components = this.get("components");
                        const typeToMove = 'website-twig-else';

                        // Filter components by type
                        const filteredComponents = components.filter(component => component.get('type') === typeToMove);
                        // Remove filtered components from the original array
                        const remainingComponents = components.filter(component => component.get('type') !== typeToMove);
                        // Concatenate the remaining components and the filtered components to move
                        const reorderedComponents = remainingComponents.concat(filteredComponents);

                        this.components().remove(this.components().filter(() => true));
                        this.components().add(reorderedComponents);

                        this.view?.bindTwig();
                    }
                })
            }
        },

        view: {
            bindTwig() {

                const css = {
                    display: "inline-block",
                    minWidth: "50px",
                    fontFamily: "monospace",
                    color: "#0090e3",
                    cursor: "text",
                    outline: "none",
                    border: "none",
                    background: "rgba(0, 0, 0, 0.05)",
                    padding: "0px 5px",
                    borderRadius: "5px",
                    fontSize: "1rem",
                    userSelect: "none",
                    whiteSpace: "nowrap",
                };

                const attributes = this.model.get('attributes');
                const item = $(`<span contenteditable="true">${attributes.item || "item"}</span>`).on("keydown", (e) => { if (e.keyCode == 13) { e.preventDefault(); } })
                    .on("input", (e) => {
                        const attributes = this.model.get('attributes');
                        attributes.item = $(e.target).text();
                        this.model.setAttributes(attributes);
                    }).css(css);
                const arrr = $(`<span contenteditable="true">${attributes.arrr || "array"}</span>`).on("keydown", (e) => { if (e.keyCode == 13) { e.preventDefault(); } })
                    .on("input", (e) => {
                        const attributes = this.model.get('attributes');
                        attributes.arrr = $(e.target).text();
                        this.model.setAttributes(attributes);
                    }).css(css);

                this.$el.find(".twig").remove();

                this.$el
                    .prepend(
                        $(`<div class="twig">`)
                            .css({
                                display: "inline-block",
                                minWidth: "50px",
                                fontFamily: "monospace",
                                fontSize: '1.1em',
                                color: "#b942f5",
                            })
                            .append("{% for ", item, " in ", arrr, " %}",)
                    )
                    .append(
                        $(`<span class="twig">{% endfor %}</span>`)
                            .css({
                                display: "inline-block",
                                minWidth: "50px",
                                fontFamily: "monospace",
                                fontSize: '1.1em',
                                color: "#b942f5",
                            })
                    )


            },
            init: function () {
                setTimeout(() => {
                    this.bindTwig();
                }, 200)
            }
        }
    } as AddComponentTypeOptions);

    Components.addType("website-twig-else", {
        model: {
            defaults: {
                draggable: "[data-gjs-type=website-twig-if],[data-gjs-type=website-twig-for]",
                components: [
                    {
                        type: "website-twig-fragment"
                    },
                ]
            }
        },
        view: {
            init: function () {
                setTimeout(() => {
                    this.$el.prepend(
                        $(`<span>{% else %}</span>`)
                            .css({
                                display: "inline-block",
                                minWidth: "50px",
                                fontFamily: "monospace",
                                fontSize: '1.1em',
                                color: "#b942f5",
                            })
                    );
                }, 400)
            }
        }
    });


    BlockManager.add("twig-if", {
        category: "Twig",
        label: "Twig If",
        content: {
            type: "website-twig-if",
        }
    });
    BlockManager.add("twig-for", {
        category: "Twig",
        label: "Twig For",
        content: {
            type: "website-twig-for",
        }
    });
    BlockManager.add("twig-else", {
        category: "Twig",
        label: "Twig Else",
        content: {
            type: "website-twig-else",
        }
    });
    BlockManager.getCategories().forEach((category, index) => {
        if (category.attributes.id === 'Twig') {
            category.attributes.order = 0;
        } else {
            category.attributes.open = false;
            category.attributes.order = index + 1;
        }
    });
    BlockManager.render();


    Panels.addButton("sidebar-panel", {
        id: "pages",
        label: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-easel" viewBox="0 0 16 16">
        <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0M2 3v7h12V3z"/>
      </svg>`,
        togglable: false,
        context: "sidebar",
        command: {
            run: function () {
                $(".editor-sidebar .pages-manager").removeClass("hide");
            },
            stop: function () {
                $(".editor-sidebar .pages-manager").addClass("hide");
            }
        }
    });


    const pagesContainer = $(`<div class="editor-layout pages-manager hide p-2 flex-wrap gap-2">`);
    $(".editor-sidebar").append(pagesContainer);

    Pages.Register(editor, {
        appendTo: ".pages-manager",
        pages: __.website.pages || []
    });
    editor.Commands.add("pages:test-variable", {
        run: function (editor, sender, opts) {
            return __.website.testVariable?.(opts);
        }
    });
    editor.Commands.add("pages:delete", {
        run: function (editor, sender, opts) {
            return __.website.deletePage?.(opts);
        }
    });


    editor.on("component:add", function (e: Component) {

        if (e.get("type") == "bs-navbar-nav") {
            updateMenu(e);
        }
        if (e.get("type") == "bs-nav-item") {
            const parent = e.parent({
                type: "bs-navbar-nav",
            });

            if (parent) {
                updateMenu(parent);
            }

        }
    });


    function updateMenu(navbar: Component) {

        const navitems = navbar.components().filter(component => component.get("type") == "bs-nav-item");

       // console.log(JSON.parse(JSON.stringify(navitems)));
    }

}

