import { Editor, Component } from "grapesjs";


export const Register = (editor: Editor) => {


    editor.Components.addType("product-wrapper", {
        extend: "group",
        model: {
            defaults: {
                tagName: "section",
                draggable: false,
                editable: false,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                attributes: {
                    class: "product-wrapper d-flex flex-wrap"
                },
                components: [
                    // carousel
                    {
                        type: "product-carousel",
                        components: [
                            {
                                type: 'product-images',
                                components: [
                                    {
                                        type: "product-image",
                                    },
                                    {
                                        type: "product-image"
                                    }
                                ]
                            },
                            {
                                type: "bs-carousel-indicators",
                                draggable: false,
                                droppable: false,
                                typeInner: "product-images",
                                typeItem: "product-image",
                            },
                            {
                                type: "bs-carousel-control-prev",
                                draggable: false,
                                editable: false,
                                removable: false,
                            },
                            {
                                type: "bs-carousel-control-next",
                                draggable: false,
                                editable: false,
                                removable: false,
                            }
                        ],

                    },
                    // product information
                    {
                        tagName: "div",
                        draggable: false,
                        editable: false,
                        removable: false,
                        attributes: {
                            class: "product-information"
                        },
                        components: [
                            {
                                type: 'header',
                                draggable: false,
                                editable: false,
                                removable: false,
                                components: [
                                    {
                                        type: "product-title"
                                    },
                                    {
                                        type: 'product-price'
                                    },
                                    {
                                        type: 'product-category'
                                    }
                                ]
                            },
                            {
                                type: 'product-description'
                            }
                        ]
                    }]
            },
        }
    });

    editor.Components.addType("product-carousel", {
        extend: "bs-carousel",
        model: {
            defaults: {
                tagName: "div",
                draggable: false,
                editable: false,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                attributes: {
                    class: "carousel slide product-carousel",
                    id: Date.now().toString(16)
                },
            },
        }
    });

    editor.Components.addType("product-images", {
        extend: "bs-carousel-inner",
        model: {
            defaults: {
                tagName: "div",
                draggable: false,
                editable: false,
                droppable: "div.carousel-item",
                removable: false,
                copyable: false,
                moveable: false,
                styles: `max-width: 600px;width: 100%;`,
            },
        }
    });

    editor.Components.addType("product-image", {
        extend: "bs-carousel-item",
        model: {
            defaults: {
                tagName: "div",
                draggable: "div.carousel-inner",
                editable: false,
                droppable: false,
                copyable: false,
                moveable: false,
                stylable: false,
                attributes: {
                    class: "carousel-item"
                },
                components: {
                    type: "image",
                    draggable: false,
                    editable: true,
                    droppable: false,
                    removable: false,
                    copyable: false,
                    moveable: false,
                    stylable: false,
                    attributes: {
                        class: "d-block w-100",
                        src: "/include/Buildin/images/placeholder.svg"
                    }
                }
            }
        }
    });

    editor.Components.addType("product-input", {

        view: {
            init() {
                this.$el.val(this.model.components()?.at(0)?.get("content") || "");
            },
            events: {
                input: 'handleInputUpdate',
            } as any,
            handleInputUpdate(ev) {
                this.model.components(ev.target.value);
            }
        }
    });

    editor.Components.addType("product-title", {
        extend: "product-input",
        model: {
            defaults: {
                tagName: "textarea",
                draggable: false,
                editable: true,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                stylable: false,
                attributes: {
                    class: "fw-semibold fs-2 d-block",
                    placeholder: "Enter product title..."
                },
                components: "Jagung jumbo"
            }
        },
        view: {
            events: {
                input: 'handleInputUpdate',
            } as any,
            handleInputUpdate(ev) {
                $(ev.target).css('height', 50);
                this.model.components(ev.target.value);
                const height = Math.max(ev.target.scrollHeight, 50) + 'px';
                $(ev.target).css('height', height);
            }
        }
    });
    editor.Components.addType("product-description", {
        extend: "product-input",
        model: {
            defaults: {
                tagName: "textarea",
                draggable: false,
                editable: true,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                stylable: false,
                attributes: {
                    class: "d-block",
                    placeholder: "Enter product description...",
                    style: "overflow: hidden;"
                }
            }
        },
        view: {
            events: {
                input: 'handleInputUpdate',
            } as any,
            handleInputUpdate(ev) {
                $(ev.target).css('height', 35);
                this.model.components(ev.target.value);
                const height = Math.max(ev.target.scrollHeight, 35) + 'px';
                $(ev.target).css('height', height);
            }
        }
    });
    editor.Components.addType("product-price", {

        model: {
            defaults: {
                tagName: "input",
                draggable: false,
                editable: true,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                stylable: false,
                attributes: {
                    class: "fw-semibold text-danger d-inline-block",
                    placeholder: "123.456"
                }
            }
        },
        view: {

            events: {
                input: 'handleInputUpdate',
            } as any,
            handleInputUpdate(ev) {

                ev.target.value = ev.target.value.replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                this.model.components(ev.target.value);

            }
        }
    });
    
    editor.Components.addType('product-category', {
        extend: "product-input",
        model: {
            defaults: {
                tagName: 'input',
                draggable: false,
                editable: true,
                droppable: false,
                removable: false,
                copyable: false,
                moveable: false,
                stylable: false,

                attributes: {
                    class: 'fw-light ms-3 text-muted',
                    placeholder: "Enter product category"
                }
            }
        }
    });
}