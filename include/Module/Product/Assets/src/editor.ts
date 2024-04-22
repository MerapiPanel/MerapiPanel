import { Editor } from "grapesjs";
import * as Component from "./partial/component";
import { post } from '@il4mb/merapipanel/http';

const editor = (window as any).editor;
const __: {
    product?: {
        id: string
        title: string
        price: string
        category: string
        description: string
        data: {
            components: any
            css: string
        }
        status: number
        post_date: string
        update_date: string
        author_id: string
        author_name: string
    }
} = (window as any).__;



const { endpoints }: { endpoints: { add: string, update: string } } = editor;

editor.callback = function (data: any) {



    post(__.product ? endpoints.update : endpoints.add, { data: JSON.stringify(data), ...{ id: __.product ? __.product.id : "" } }).then((res) => {

        if (res.code === 200) {
            if (__.product) {
                this.resolve("Product updated successfully");
            } else {
                this.resolve("Product saved successfully");
                __.product = res.data as any;
                window.history.replaceState("", "", window.location.href.replace("new", "edit") + "/" + (res.data as any).id);
            }
        } else {
            if (__.product) {
                this.reject(res.message || "Failed to update product");
            } else {
                this.reject(res.message || "Failed to save product");
            }
        }
    }).catch((err) => {
        if (__.product) {
            this.reject(err.message || "Failed to update product");
        } else {
            this.reject(err.message || "Failed to save product");
        }
    });
};

editor.onReady = (editor: Editor) => {

    Component.Register(editor);

    if (__.product) {
        editor.setComponents(__.product.data.components);
        editor.addStyle(__.product.data.css);
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
