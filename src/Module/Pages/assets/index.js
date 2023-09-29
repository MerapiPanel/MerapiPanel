import $ from "jquery";
import Merapi from "../../../base/assets/merapi";
import { trim } from "lodash";




const createPage = (args = {}) => {

    const options = Object.assign({
        template: null,
        endpoint: null
    }, args);


    if (options.template) {

        Merapi.get(options.template).then((data) => {
            console.log(data)
        })

    }

    var name = "", slug = "";

    const modal = Merapi.createModal('<i class="fa-solid fa-star-of-life"></i> Create New Page');

    const container = $(`<div class="container">
        <div class='mb-3'>
            <label for="input-title" class="font-bold">Title</label>
            <input class='text-input' id="input-title" type="text" placeholder="Enter title">
            <small>title just for reference records</small>
        </div>
        <div class='mb-3'>
            <label for="input-slug" class="font-bold">Slug</label>
            <input class='text-input' id="input-slug" type="text" placeholder="url-slug">
        </div>
    </div>`);

    $(container).find("#input-title").on("input", function () {
        name = $(this).val();
        slug = "/page/" + trim($(this).val().replace(/^\/page/g, '').replace(/[^a-z0-9]+/gi, '-').toLowerCase(), "-");
        $(container).find("#input-slug").val(slug);
    })
    let delay = null;
    $(container).find("#input-slug").on("input", function () {
        slug = ($(this).val().replace(/^\/page/g, '').replace(/[^a-z0-9]+/gi, '-').trim().toLowerCase());
        if (slug.charAt(0) == "-") slug = slug.substring(1);
        slug = "/page/" + slug;

        $(this).val(slug);

        if (delay) clearTimeout(delay);
        delay = setTimeout(() => {
            slug = "/page/" + trim($(this).val().replace(/^\/page/g, '').replace(/[^a-z0-9]+/gi, '-').toLowerCase(), "-");
            $(this).val(slug);
        }, 500);
    })


    modal.container.body.append(container);
    modal.setAction("+", {
        text: "create",
        class: "btn btn-primary",
        callback: () => {

            if (!name) {
                Merapi.toast("Please enter title", 5, 'text-warning');
                return;
            }
            const form = new FormData();
            form.append("title", name);
            form.append("slug", slug);

            Merapi.post(options.endpoint, form).then((data, text, xhr) => {
                if (xhr.status === 200) {
                    Merapi.toast(data.message, 5, 'text-success');
                    modal.hide();
                    window.location.reload();
                } else {
                    Merapi.toast(data.message, 5, 'text-danger');
                }
            })
        }
    })
    modal.show();
}



const pageManager = {
    createPage: createPage
};

Merapi.assign("pages", pageManager);