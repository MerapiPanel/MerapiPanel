import $ from "jquery";
import Merapi from "../../../base/assets/merapi";
import { trim } from "lodash";




const createPage = () => {

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
        slug = trim($(this).val().replace(/[^a-z0-9]+/gi, '-').toLowerCase(), "-");
        $(container).find("#input-slug").val(slug);
    })
    let delay = null;
    $(container).find("#input-slug").on("input", function () {
        slug = $(this).val().replace(/[^a-z0-9]+/gi, '-').toLowerCase();
        $(this).val(slug);

        if (delay) clearTimeout(delay);
        delay = setTimeout(() => {
            $(this).val(trim($(this).val().replace(/[^a-z0-9]+/gi, '-').toLowerCase(), "-"));
        }, 1000);
    })

    modal.container.body.append(container);

    modal.setAction("+", {
        text: "create",
        class: "btn btn-primary",
        callback: () => {
            alert();
        }
    })
    modal.show();
}



const pageManager = {
    createPage: createPage
};

Merapi.assign("pages", pageManager);