import { Editor } from "grapesjs";
import { toast } from "@il4mb/merapipanel/toast";
import * as http from "@il4mb/merapipanel/http";
import { __MP } from "../../../../Buildin/src/main";

const editor: {
    config: any,
    fetchBlockURL: string,
    [key: string]: any
} = (window as any).editor;

const __: __MP = (window as any).__;

const templates: {
    data: {
        id: number,
        title: string,
        description: string,
        components: any,
        css: string,
        user_id?: string,
        user_name?: string,
        post_date: string,
        update_date: string
    },
    saveURL: string,
    [key: string]: any
} = __.templates;


editor.onReady = function (editor: Editor) {

    const { BlockManager, Components } = editor;

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
                droppable: true,
                components: [
                    {
                        type: "website-twig-fragment"
                    }
                ]
            }
        }
    })
    Components.addType("website-twig-for", {
    })
    Components.addType("website-twig-else", {
        model: {
            defaults: {
                droppable: true,
                components: [
                    "{% else %}",
                    {
                        type: "website-twig-fragment"
                    },
                ]
            }
        }
    });


    BlockManager.add("twig-if", {
        category: "Twig",
        label: "Twig If",
        content: {
            type: "website-twig-if",
        }
    })
    BlockManager.add("twig-for", {
        category: "Twig",
        label: "Twig For",
        content: {
            type: "website-twig-for",
        }
    })
    BlockManager.add("twig-else", {
        category: "Twig",
        label: "Twig Else",
        content: {
            type: "website-twig-else",
        }
    })
    BlockManager.getCategories().forEach((category, index) => {
        if (category.attributes.id === 'Twig') {
            category.attributes.order = 0;
        } else {
            category.attributes.open = false;
            category.attributes.order = index + 1;
        }
    });
    BlockManager.render();
}



editor.callback = function (data: { components: any, css: string }) {

    const gjsEditor: Editor = this.editor;
    const modal = gjsEditor.Modal;
    let is_submit = false;

    const content = $("<div class='needs-validation'>")
        .append(
            $('<div class="form-group mb-3">')
                .append(`<label for="title">title</label>`)
                .append(`<input type="text" class="form-control" id="title" placeholder="Enter title" value="${templates.data.title || ''}" required/>`)
                .append(`<div class="invalid-feedback">Please provide a valid title.</div>`),
        )
        .append(
            $('<div class="form-group mb-3">')
                .append(`<label for="description">description</label>`)
                .append(`<textarea type="text" class="form-control" id="description" placeholder="Enter description" rows="3">${templates.data.description || ''}</textarea>`)
                .append(`<div class="invalid-feedback">Please provide a valid description.</div>`)
        ).append(
            $('<div class="form-group mb-3">')
                .append(`<button type="submit" class="btn btn-primary w-100">Submit</button>`)
        )

    modal.open({
        title: 'Page attribute',
        content: content
    });
    modal.onceClose(() => {
        if (!is_submit) {
            this.reject();
            return;
        }
        this.resolve();
    });

    content.find('[type="submit"]').on('click', function () {

        const title: string = content.find('#title').val() as string;
        const description: string = content.find('#description').val() as string;

        if (!title || (title || "").length <= 0) {
            toast('Title is required', 5, 'text-danger');
            return;
        }


        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('components', JSON.stringify(data.components));
        formData.append('css', data.css);
        if (templates.data.id) formData.append('id', (templates.data.id as any).toString());

        http.post(templates.saveURL, formData).then(function (response) {
            if (response.code !== 200) {
                toast(response.message, 5, 'text-danger');
                return;
            }
            is_submit = true;
            modal.close();
        }).catch(error => {
            toast(error.message || "Error: Please try again!", 5, 'text-danger');
        });

    });

}