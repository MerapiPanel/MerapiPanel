import { __MP } from "../../../../Buildin/src/main";
const __: __MP = (window as any).__;

__.routes = {


    templateFetchURL: null,

    render: function (container: HTMLElement | JQuery, endpoint: string) {

        function createComponent(route: any) {

            return $(`<li class="mb-3">`)
                .append(
                    $(`<div class="d-flex align-items-end flex-wrap${route.id === null ? ' text-primary' : ''}">`)
                        .append(
                            $(`<div>`)
                                .append(`<b>${route.title}</b>`)
                                .append(
                                    $(`<small class="text-primary d-block" style="cursor:pointer">${toFullURL(route.route)}</small>`).on('click', function () {
                                        window.open(toFullURL(route.route), '_blank');
                                    })
                                )
                        )
                        .append(
                            route.id === null ? "" : $(`<div class="ms-auto btn-group" role="group" aria-label="Basic example">`)
                                .append(
                                    route.template_id === null ? "" :
                                        $(`<button type="button" class="btn btn-sm px-4 btn-outline-primary">Edit Template</button>`)
                                            .on('click', function () {
                                                __.routes.fire('editTemplate', route);
                                            }),
                                    $(`<button type="button" class="btn btn-sm px-4 btn-outline-primary">Edit</button>`)
                                        .on('click', function () {
                                            if (__.routes.fire("pre:edit", route).cancelBubble) return;
                                            __.routes.edit(route);
                                        }),
                                    $(`<button type="button" class="btn btn-sm px-4 btn-outline-danger">Delete</button>`)
                                        .on('click', function () {
                                            __.routes.delete(route);
                                        })
                                )
                        )
                )
        }

        function toFullURL(url: string) {

            return window.location.protocol + "//" + window.location.host + (url.charCodeAt(0) === 47 ? url : "/" + url)
        }
        __.http.get(endpoint, {}).then(function (response) {
            $(container).html("").append((response.data as any[]).map(createComponent));
        })

    },


    add: function () {

        const content = $(`<form class='needs-validation'>`)
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="title">Title</label>`)
                    .append(`<input type="text" class="form-control" id="title" placeholder="Enter title" required/>`)
                    .append(`<div class="invalid-feedback">Please provide a valid title.</div>`)
            )
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="template">Template</label>`)
                    .append(
                        $(`<select type="text" class="form-select" id="template"></select>`).append(
                            $(`<option value="">-- Select Template --</option>`)
                        )
                    )
            ).append(

                $(`<div class="form-group mb-3">`)
                    .append(`<label for="route">Route</label>`)
                    .append(`<input type="text" class="form-control" id="route" placeholder="Enter route" required/>`)
                    .append(`<div class="invalid-feedback">Please provide a valid route.</div>`)
            );

        const modal = $("#modal-add-route").length <= 0 ? __.modal.create("Add Route", content) : __.modal.from($("#modal-add-route"));
        modal.el.prop('id', 'modal-add-route');
        modal.show();

        $(modal.el).find('#title').on('input', function () {
            const route = $(modal.el).find('#route');
            route.val("/" + ($(this).val() as string)?.replace(/[^a-zA-Z0-9_]+/g, '-')?.toLowerCase()?.replace(/-$/g, '')?.replace(/^-/, ''));
        });
        let timeout: any;
        $(modal.el).find('#route').on('input', function () {
            const value = "/" + ($(this).val() as string)?.replace(/[^a-zA-Z0-9_]+/g, '-')?.toLowerCase()?.replace(/^-/, '')?.replace(/\\|\/$/g, '');
            $(this).val(value);
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(() => {
                $(this).val(($(this).val() as string)?.replace(/-$/g, ''));
            }, 1000);
        });

        $(modal.el).find('#template').on('click', function (e) {
            e.preventDefault();
            e.currentTarget.blur();
            selectTemplates().then(function (template: any) {
                modal.el.find('#template').html('').append(`<option value="${template.id}" selected>${template.title}</option>`);
            });
        });


        return new Promise(function (resolve, reject) {

            modal.on("modal:hide", function () {
                reject();
            });
            modal.action.positive = function () {

                const title: string = modal.el.find('#title').val() as string;
                const route: string = modal.el.find('#route').val() as string;
                const template: string = modal.el.find('#template').val() as string;

                if (!(modal.el.find('form').get(0) as any).checkValidity()) {
                    __.toast('Form is not valid', 5, 'text-danger');
                    return;
                }

                if (!title || (title || "").length <= 0) {
                    __.toast('Title is required', 5, 'text-danger');
                    return;
                }

                if (!route || (route || "").length <= 0) {
                    __.toast('Route is required', 5, 'text-danger');
                    return;
                }

                resolve({ title: title, route: route, template: template });
                modal.hide();
                ((modal.el.find('form').get(0) as any) as any).reset();

            }

        });

    },


    edit: function (route: any) {
        const content = $(`<form class="needs-validation">`)
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="id">id</label>`)
                    .append(`<input type="text" class="form-control" id="id" placeholder="Enter id" value="${route.id}" readonly/>`)
            )
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="title">Title</label>`)
                    .append(`<input type="text" class="form-control" id="title" placeholder="Enter title" value="${route.title}" required/>`)
                    .append(`<div class="invalid-feedback">Please provide a valid title.</div>`)
            )
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="template">Template</label>`)
                    .append(
                        $(`<select type="text" class="form-select" id="template"></select>`).append(
                            $(`<option value="${route.template_id}">${route.template_title}</option>`)
                        )
                    )
            )
            .append(
                $(`<div class="form-group mb-3">`)
                    .append(`<label for="route">Route</label>`)
                    .append(`<input type="text" class="form-control" id="route" placeholder="Enter route" value="${route.route}" ${route.route == "/" ? "disabled" : ""} required/>`)
                    .append(`<div class="invalid-feedback">Please provide a valid route.</div>`)
            )


        const modal = $("#modal-edit-route").length <= 0 ? __.modal.create("Edit Route", content) : __.modal.from($("#modal-edit-route"));
        modal.el.prop('id', 'modal-edit-route');
        modal.show();





        modal.el.find('#id').val(route.id);
        modal.el.find('#title').val(route.title);
        modal.el.find('#route').val(route.route);



        $(modal.el).find('#title').on('input', function () {
            const route = $(modal.el).find('#route');
            route.val("/" + ($(this).val() as string)?.replace(/[^a-zA-Z0-9_]+/g, '-')?.toLowerCase()?.replace(/-$/g, '')?.replace(/^-/, ''));
        });
        let timeout: any;
        $(modal.el).find('#route').on('input', function () {
            const value = "/" + ($(this).val() as string)?.replace(/[^a-zA-Z0-9_]+/g, '-')?.toLowerCase()?.replace(/^-/, '')?.replace(/\\|\/$/g, '');
            $(this).val(value);
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(() => {
                $(this).val(($(this).val() as string)?.replace(/-$/g, ''));
            }, 1000);
        });


        $(modal.el).find('#template').on('click', function (e) {
            e.preventDefault();
            e.currentTarget.blur();
            selectTemplates(route.template).then(function (template: any) {
                modal.el.find('#template').html('').append(`<option value="${template.id}" selected>${template.title}</option>`);
            });
        });

        modal.on("modal:hide", function () {
            modal.el.find('input').each(function () {
                $(this).val('').removeClass('is-valid is-invalid');
            })
        });

        modal.action.positive = function () {

            const id: string = modal.el.find('#id').val() as string;
            const title: string = modal.el.find('#title').val() as string;
            const route: string = modal.el.find('#route').val() as string;
            const template: string = modal.el.find('#template').val() as string;

            if (!(modal.el.find('form').get(0) as any).checkValidity()) {
                __.toast('Provide a valid form', 5, 'text-danger');
                return;
            }

            if (!id || (id || "").length <= 0) {
                __.toast('id is required', 5, 'text-danger');
                return;
            }

            if (!title || (title || "").length <= 0) {
                __.toast('Title is required', 5, 'text-danger');
                return;
            }

            if (!route || (route || "").length <= 0) {
                __.toast('Route is required', 5, 'text-danger');
                return;
            }

            __.routes.fire('edit', { id: id, title: title, route: route, template: template });
            modal.hide();

            (modal.el.find('form').get(0) as any).reset();

        };


    },


    delete: function (route: any) {

        __.dialog.danger("Are you sure?", "Are you sure to delete this route <b>" + route.title + '</b> with id <i>' + route.id + '</i>')
            .then(function () {
                __.routes.fire('delete', route);
            }).catch(function () {
                __.toast('Cancelled', 5, 'text-warning');
            })
    },

    callbacks: {},

    on: function (event: string, callback: (event: any) => void) {
        if (!__.routes.callbacks[event]) {
            __.routes.callbacks[event] = [];
        }
        __.routes.callbacks[event].push(callback);
    },
    fire(event: string, ...args: any[]) {

        const _event = new Event(event);
        if (__.routes.callbacks[event]) {

            __.routes.callbacks[event].forEach(function (callback: Function) {
                if (_event.cancelBubble) return;
                callback(_event, ...args);
            });
        }
        return _event;
    }
}




function selectTemplates(template: any = null) {

    const content = $(`<div id="content" class="row row-cols-1 row-cols-md-2 g-4"></div>`);
    const modal = $("#modal-select-template").length <= 0 ? __.modal.create("Select Template", content) : __.modal.from($("#modal-select-template"));
    modal.el.prop('id', 'modal-select-template');
    modal.show();
    modal.el.find("#content").append(
        $(`<div id='loading-container'>`)
            .css({ height: '150px', width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'absolute', top: 0, left: 0, zIndex: 5 })
            .append(`<i class="fa-solid fa-spinner fa-spin fa-2x"></i>`),
    );

    function createTemplate(data: any) {

        return $(`<label class="col d-flex align-items-center" style="max-width: 14rem; gap: 1rem;">`)
            .append(
                $(`<input type="radio" name="template" value="${data.id}" ${template == data.id ? 'checked' : ''}>`),
                $(`<div class="card card-body">`)
                    .append(
                        $(`<h5 class="card-title">${data.title}</h5>`)
                    )
                    .append(
                        $(`<small class="card-text">${data.description || '<span class="text-muted">No description</span>'}</small>`)
                    )
            )

    }


    var templates: any[] = [];
    __.http.get(__.routes.templateFetchURL, {}).then(function (response) {
        templates = response.data as any[];
        modal.el.find("#content").html("").append(templates.map(createTemplate));
    });

    return new Promise(function (resolve, reject) {

        modal.action.positive = {
            text: "Select",
            callback: function () {

                const id = modal.el.find('input[name="template"]:checked').val();

                if (!id) {
                    __.toast('Please select a template', 5, 'text-danger');
                    return;
                }

                const template = templates.find(x => x.id == id);
                resolve(template);

                modal.hide();
            }
        }
    });

}