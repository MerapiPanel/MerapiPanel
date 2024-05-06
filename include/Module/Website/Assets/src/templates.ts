import * as rasterizeHTML from 'rasterizehtml';
import { __MP } from "../../../../Buildin/src/main";
const __: __MP = (window as any).__;


type Data = {
    id: number
    title: string
    description: string
    data: string
    user_id?: string
    user_name?: string
    post_date: string
    update_date: string
}


function createComponent(data: Data) {


    let card = $(`<div class="card" style="width: 18rem;">`)
        .append(
            $(`<canvas class="card-img-top">`),
            $(`<div class='loading-container'>`)
                .css({
                    height: '150px',
                    width: '100%',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    zIndex: 5
                })
                .append(`<i class="fa-solid fa-spinner fa-spin fa-2x"></i>`),
            $(`<div class="card-body">`)
                .append(`<h5 class="card-title">${data.title}<small class="text-muted ms-2"><i>#${data.id}</i></small></h5>`)
                .append(`<p class="card-text">${data.description || '<span class="text-muted">No description</span>'}</p>`)
        ).append(
            $(`<div class="dropdown position-absolute top-0 end-0">`)
                .append(
                    $(`<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>`)
                        .append($(`<ul class="dropdown-menu dropdown-menu-end">`)
                            .append(
                                $(`<li class='dropdown-item'>Edit</li>`)
                                    .on('click', () => {
                                        __.templates.fire('edit', data);
                                    }),
                                $(`<li class='dropdown-item'>Delete</li>`).on('click', () => __.templates.delete(data))
                            )
                        )
                )
        )

    rasterizeHTML.drawURL(__.templates.previewURL.replace(':id', data.id.toString()), card.find('canvas')[0], {
        width: 1024,
        height: 500,
        zoom: 0.3,
        cache: 'none'
    }).finally(function () {
        card.find(".loading-container").remove();
    });

    return card;
}



__.templates = {
    previewURL: null,
    render(container: HTMLElement | JQuery, endpoint: string) {
        __.http.get(endpoint, {}).then(function (response) {
            $(container).html("").append((response.data as any[]).map(createComponent));
        }).catch(error => {
            __.toast(error.message || "Error: Please try again!", 5, 'text-danger');
        })
    }

}



__.templates.delete = function (data: Data) {

    const content = $('<div>').append(
        $(`<p>`).append("Are you sure to delete this page <b>" + data.title + '</b> with id <i>' + data.id + '</i>')
    )
    __.dialog.danger("Are you sure?", content)
        .then(() => {
            __.templates.fire('delete', data);
        })
}


__.templates.add = function () {

    const content = $(`<form class='needs-validation'>`)
        .append(
            $(`<div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Enter title" required>
                <div class="invalid-feedback">Please provide a valid title.</div>
            </div>`),
            $(`<div class="form-group">
                <label for="description">Description</label>
                <textarea type="text" class="form-control" id="description" placeholder="Enter description" rows="3"></textarea>
            </div>`),
        )

    const modal = $("#modal-add-page").length <= 0 ? __.modal.create("Add Page", content) : __.modal.from($("#modal-add-page"));
    modal.el.prop('id', 'modal-add-page');

    modal.action.positive = function () {

        const title: string = modal.el.find('#title').val() as string;
        const description: string = modal.el.find('#description').val() as string;

        if (!title || (title || "").length <= 0) {
            __.toast('Title is required', 5, 'text-danger');
            return;
        }

        __.templates.fire('add', {
            title: title,
            description: description
        });
        modal.hide();

        (modal.el.find('form').get(0) as any).reset();
    }


    modal.show();
}

__.templates.events = {};

__.templates.fire = function (event: string, data: Data) {

    if (__.templates['on' + event]) {
        __.templates['on' + event].forEach(function (callback: any) {
            callback.apply(null, [data]);
        });
    }
}

__.templates.on = function (event: string, callback: (data: Data) => void) {
    if (!__.templates['on' + event]) {
        __.templates['on' + event] = [];
    }
    __.templates['on' + event].push(callback);
}