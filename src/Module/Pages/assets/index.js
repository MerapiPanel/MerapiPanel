import { trim } from "lodash";




const createPage = (args = {}) => {

    const options = Object.assign({
        template: null,
        token: null,
        endpoint: null
    }, args);


    if (options.template) {

        merapi.http.get(options.template).then((data) => {
            console.log(data)
        })

    }

    var name = "", slug = "";

    const modal = merapi.Modal.create('<i class="fa-solid fa-star-of-life"></i> Create New Page');

    modal.content = $(`<div class="container"><div class='mb-3'><label for="input-title" class="font-bold">Title</label><input class='text-input' id="input-title" type="text" placeholder="Enter title"><small>title just for reference records</small></div><div class='mb-3'><label for="input-slug" class="font-bold">Slug</label><input class='text-input' id="input-slug" type="text" placeholder="url-slug"></div></div>`);

    $(modal.content).find("#input-title").on("input", function () {
        name = $(this).val();
        slug = "/page/" + trim($(this).val().replace(/^\/page/g, '').replace(/[^a-z0-9]+/gi, '-').toLowerCase(), "-");
        $(modal.content).find("#input-slug").val(slug);
    })
    let delay = null;
    $(modal.content).find("#input-slug").on("input", function () {
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


    //modal.container.body.append(container);


    modal.action.positive = {
        text: "create",
        class: "btn btn-primary",
        callback: () => {

            if (!name) {
                merapi.toast("Please enter title", 5, 'text-warning');
                return;
            }
            const form = new FormData();
            form.append("title", name);
            form.append("slug", trim(slug.replace(/^\/page\//, ''), "-"));

            merapi.http.post(options.endpoint, form).then((data, text, xhr) => {
                if (xhr.status === 200) {
                    merapi.toast(data.message, 5, 'text-success');
                    modal.hide();
                    window.location.reload();
                } else {
                    merapi.toast(data.message, 5, 'text-danger');
                }
            })
        }
    }
    modal.show();
}


const assignTemplate = (args = {}) => {

    const options = Object.assign({
        id: null,
        title: null,
        slug: null,
        token: null,
        endpoint: {
            save: null,
            template: null
        }
    }, args);


    if (!options.id) {
        merapi.toast("Action error because no page id passed", 5, 'text-danger');
        return;
    }
    if (!options.endpoint) {
        merapi.toast("Action error because no endpoint passed", 5, 'text-danger');
        return;
    }
    if (!options.endpoint.save) {
        merapi.toast("Action error because no save endpoint passed", 5, 'text-danger');
        return;
    }
    if (!options.endpoint.template) {
        merapi.toast("Action error because no template endpoint passed", 5, 'text-danger');
        return;
    }


    const modal = merapi.Modal.create('<i class="fa-solid fa-code-pull-request"></i> Assign Template');
    modal.content = $(`<div class='mb-3'><div class='mb-4 flex'><i class="text-3xl rotate-[-30deg] p-2 fa-solid fa-thumbtack"></i><div class='ps-3'><h3>page: ${options.title}</h3><small>${options.slug}</small></div></div><p class='mb-2'>Chose template</p><div class='max-h-[50vh] overflow-auto p-1' id='template-wrapper'></div></div>`);

    merapi.http.get(options.endpoint.template).then((response) => {

        const wrapper = modal.content.find("#template-wrapper");
        const templates = response.data.templates;

        for (let i in templates) {

            const template = templates[i];
            const element = $(`<label class='px-5 py-2 shadow mb-3 flex items-center'>
            <div><h4>${template.name}</h4><p>${template.description}</p><small>${template.id}</small></div>
            <input class='!w-[1.3rem] !h-[1.3rem] ms-auto' type='radio' name='template' value='${template.id}'>
            </label>`);

            wrapper.append(element);
        }

        modal.action.positive = {
            text: "Assign",
            class: "btn btn-primary",
            callback: () => {
                const template = wrapper.find("input:checked").val();

                if (!template) {
                    merapi.toast("Please choose template", 5, 'text-warning');
                    return;
                }

                const form = new FormData();
                form.append("template_id", template);
                form.append("page_id", options.id);
                form.append("m-token", options.token);

                merapi.http.post(options.endpoint.save, form).then((data, text, xhr) => {
                    if (xhr.status === 200) {
                        merapi.toast(data.message, 5, 'text-success');
                        window.location.reload();
                    } else {
                        merapi.toast(data.message, 5, 'text-danger');
                    }
                })
            }
        }

        modal.show();
    })
}

//const pageManager = ;

merapi.assign("Pages", {
    createPage: createPage,
    assignTemplate: assignTemplate
});