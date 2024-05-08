import { __MP } from "../../../../Buildin/src/main";
import { Editor } from "grapesjs";
import * as Component from "./partial/component";
import { post } from '@il4mb/merapipanel/http';

const __: __MP = (window as any).__;
type MProductType = {
    data: {
        id?: string
        title?: string
        price?: string
        category?: string
        description?: string
        data?: {
            components: any
            css: string
        }
        status?: number
        post_date?: string
        update_date?: string
        [key: string]: any
    }
    endpoints: {
        fetchAll: string
        fetch: string
        view: string
        update: string
        delete: string
        edit: string
        add: string
    }
    render: Function
    el: HTMLElement
    [key: string]: any
}
const MProduct: MProductType = __.MProduct;
if (!MProduct.data) {
    MProduct.data = {} as any;
}
const editor = (window as any).editor;

// const __: {
//     product?: {
//         id: string
//         title: string
//         price: string
//         category: string
//         description: string
//         data: {
//             components: any
//             css: string
//         }
//         status: number
//         post_date: string
//         update_date: string
//         author_id: string
//         author_name: string
//     }
// } = (window as any).__;


const endpoints = MProduct.endpoints;

editor.callback = function (data: any) {



    post(MProduct.data.id ? endpoints.update : endpoints.add, { data: JSON.stringify(data), ...{ id: MProduct.data ? MProduct.data.id : "" } })
        .then((res) => {

            if (res.code === 200) {
                if (MProduct.data.id) {
                    this.resolve("Product updated successfully");
                } else {
                    this.resolve("Product saved successfully");

                    if (typeof res.data == "object") {
                        Object.keys(res.data).forEach((key) => {
                            MProduct.data[key] = res.data[key];
                        });
                        if (MProduct.data.id) {
                            window.history.replaceState(null, "", endpoints.edit.replace("{id}", MProduct.data.id));
                        }
                    }
                }
            } else {
                if (MProduct.data) {
                    this.reject(res.message || "Failed to update product");
                } else {
                    this.reject(res.message || "Failed to save product");
                }
            }
        }).catch((err) => {
            if (MProduct.data) {
                this.reject(err.message || "Failed to update product");
            } else {
                this.reject(err.message || "Failed to save product");
            }
        });
};

editor.onReady = (editor: Editor) => {

    Component.Register(editor);

    if (MProduct.data.id && MProduct.data.data) {
        editor.setComponents(MProduct.data.data?.components);
        editor.addStyle(MProduct.data.data?.css);
    } else {

        editor.setComponents([
            {
                type: "product-wrapper"
            },
            {
                tagName: "br"
            },
            {
                type: "container",
                components: []
            }
        ]);

        editor.addStyle(`
    .product-wrapper {
        display: flex;
        flex-wrap: wrap;
        padding-top: 20px;
        max-width: 1200px;
        margin-top: 0px;
        margin-right: auto;
        margin-bottom: 0px;
        margin-left: auto;
    }
    
    .product-wrapper .carousel {
        flex-grow: 1;
        flex-shrink: 1;
        flex-basis: 600px;
    }
    
    .product-wrapper .product-information {
        flex-basis: 600px;
        padding-top: 0.8rem;
        padding-right: 0.8rem;
        padding-bottom: 0.8rem;
        padding-left: 0.8rem;
    }
    
    .carousel-item img {
        width: 100%;
        object-fit: cover;
        height: 400px;
    }
    textarea {
        width: 100%;
        height: auto;
        resize: none;
    }
    
    @media screen and (max-width: 768px) {
        .product-wrapper .product-information {
            margin-left: 0px;
        }
    }
    input, textarea, select {
        border: none;
    }`);
    }


    editor.BlockManager.add('product-image', {

        label: 'Product Image',
        category: 'Product',

        content: {
            type: 'product-image',
        }
    });



    editor.BlockManager.getCategories().forEach((category, index) => {
        if (category.attributes.id === 'Product') {
            category.attributes.order = 0;
        } else {
            category.attributes.open = false;
            category.attributes.order = index + 1;
        }
    });
    editor.BlockManager.render()
}
