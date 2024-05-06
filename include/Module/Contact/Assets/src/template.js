const __ = window.__;
const template = __.template || {};

const icons = {
    default: `<i class="fa-solid fa-user me-2"></i>`,
    phone: `<i class="fa-solid fa-phone me-2"></i>`,
    email: `<i class="fa-solid fa-envelope me-2"></i>`,
    whatsapp: `<i class="fa-brands fa-whatsapp me-2"></i>`
}





template.events = [];


template.on = function (key, callback) {
    if (!template.events[key]) template.events[key] = [];
    template.events[key].push(callback);
}


template.off = function (key, callback) {
    if (!template.events[key]) return;
    template.events[key].splice(template.events[key].indexOf(callback), 1);
}


template.fire = function (key, data) {
    if (!template.events[key]) return;

    return new Promise((res, rej) => {
        const event = new Event(key);
        const promises = template.events[key].map(async callback => {
            try {
                if (typeof callback === 'function' && event.cancelBubble) return;
                return callback(event, data); // Return the promise
            } catch (error) {
                rej(error);
            }
        });

        // Await all promises
        Promise.all(promises).then(() => res(event)).catch(rej);
    });
}



template.render = () => {

    if (this instanceof HTMLElement) {
        template.el = this;
    }
    if (!template.el) {
        __.toast("No element found", 5, 'text-danger');
        return;
    }
    if (!template.fetchURL) {
        __.toast("No fetch URL found", 5, 'text-danger');
        return;
    }


    __.http.post(template.fetchURL, { type: template.filter || null })
        .then(function (response) {
            if (!response.data || response.data.length === 0) {
                $(template.el).html("").append($("<div class=\"d-flex justify-content-center align-items-center\">").css({
                    minHeight: "40vh"
                }).append($("<h3 class=\"fs-3 fw-semibold text-muted\">").append("<i class=\"fa-solid fa-quote-left fa-xl me-2\"></i>").append("No templates found")));
                return;
            }
            $(template.el).html("").append(response.data.map(createComponent));

        }).catch(function (error) {
            __.toast(error.message || "Something went wrong", 5, 'text-danger');
        });


    function createComponent(item) {

        return $("<li class=\"list-group-item position-relative d-flex justify-content-start align-items-center \">")
            .append(
                $(`<div>`)
                    .append('<i class="fa-regular fa-file-lines fa-2x text-muted me-2"></i>'),
                $(`<div class="ms-1">`)
                    .append(`<h4 class="mb-1">${item.name}</h4>`)
                    .append($("<div class=\"d-flex justify-content-start align-items-center\">")
                        .append(icons[item.contact.type || 'default'],)
                        .append(`<small>${item.contact.name || '<i>No name</i>'}</small>`)
                    )
            )

            .append(
                $("<div class=\"dropdown position-absolute top-0 end-0\">")
                    .append($("<button class=\"btn btn-sm dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">"),
                        $("<ul class=\"dropdown-menu dropdown-menu-end\">")
                            .append($("<li class=\"dropdown-item\" type=\"button\">")
                                .append("Edit")
                                .on('click', function () {
                                    return template.edit(item);
                                }))
                            .append($("<li class=\"dropdown-item text-danger\" type=\"button\">")
                                .append("Delete")
                                .on('click', function () {
                                    return template.delete(item);
                                })))
            )
    }

}


template.edit = (item) => {

    template.fire('edit', item)
        ?.then(() => {
            template.render();
        });

}


template.delete = (item) => {
    __.dialog.confirm("Are you sure?", `Are you sure you want to delete <b>${item.name}</b>?`)
        .then(function () {
            template.fire('delete', item)
                ?.then(() => {
                    __.toast("Template deleted successfully", 5, 'text-success');
                    template.render();
                })
                .catch(function (error) {
                    __.toast(error.message || "Something went wrong", 5, 'text-danger');
                });
        });
}