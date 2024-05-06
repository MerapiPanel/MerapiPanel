const __ = window.__;
const contact = __.contact;

const modalContent = $(`<form class="needs-validation">`)
    .append(
        $(`<div class="form-group mb-3">`)
            .append(
                $(`<label for="name" class="form-label w-100">Name</label>`)
                    .append(
                        $(`<input type="text" class="form-control" id="name" placeholder="Enter name" required>`)
                    )
            )
    )
    .append(
        $(`<div class="form-group mb-3">`)
            .append(
                $(`<label for="type" class="form-label w-100">Type</label>`)
                    .append(
                        $(`<select class="form-select" id="type">`)
                            .append(
                                $(`<option value="phone">Phone</option>`),
                                $(`<option value="email">Email</option>`),
                                $(`<option value="whatsapp">Whatsapp</option>`)
                            )
                    )
            )
    )
    .append(
        $(`<div class="form-group mb-3">`)
            .append(
                $(`<label for="address" class="form-label w-100" for="address">Phone</label>`),
                $(`<input type="text" class="form-control" id="address" name="address" pattern="^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,12}$" placeholder="Enter phone number" required>`),
                $(`<div class="invalid-feedback">Invalid phone number</div>`)
            )
    );

const placeholders = {
    phone: 'Enter phone number',
    email: 'Enter email address',
    whatsapp: 'Enter whatsapp number'
}
const labels = {
    phone: 'Phone',
    email: 'Email',
    whatsapp: 'Whatsapp'
}
const pattern = {
    phone: '^[+]?[(]?[0-9]{3}[)]?[-\\s.]?[0-9]{3}[-\\s.]?[0-9]{4,12}$',
    email: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\\.[a-zA-Z]{2,}$',
    whatsapp: '^[+]?[(]?[0-9]{3}[)]?[-\\s.]?[0-9]{3}[-\\s.]?[0-9]{4,12}$',
}
const invalidMessage = {
    phone: 'Invalid phone number',
    email: 'Invalid email address',
    whatsapp: 'Invalid whatsapp number'
}

const icons = {
    phone: `<i class="fa-solid fa-phone fa-xl me-2"></i>`,
    email: `<i class="fa-solid fa-envelope fa-xl me-2"></i>`,
    whatsapp: `<i class="fa-brands fa-whatsapp fa-xl me-2"></i>`
}

contact.events = [];


contact.on = function (key, callback) {
    if (!contact.events[key]) contact.events[key] = [];
    contact.events[key].push(callback);
}


contact.off = function (key, callback) {
    if (!contact.events[key]) return;
    contact.events[key].splice(contact.events[key].indexOf(callback), 1);
}


contact.fire = function (key, data) {
    if (!contact.events[key]) return;

    return new Promise((res, rej) => {
        const event = new Event(key);
        const promises = contact.events[key].map(async callback => {
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




contact.render = function () {

    if (this instanceof HTMLElement) {
        contact.el = this;
    }
    if (!contact.fetchURL) {
        __.toast("No fetch URL found", 5, 'text-danger');
        return;
    }

    __.http.post(contact.fetchURL, {
        'contact-type': contact.filter || null
    }).then(function (response) {

        if (!response.data || response.data.length === 0) {

            $(contact.el).html("")
                .append(
                    $(`<div class="d-flex justify-content-center align-items-center">`)
                        .css({
                            minHeight: "40vh"
                        })
                        .append(
                            $(`<h3 class="fs-3 fw-semibold text-muted">`)
                                .append(`<i class="fa-solid fa-quote-left fa-xl me-2"></i>`)
                                .append("No contacts found")
                        )
                )

            return;
        }

        $(contact.el).html("")
            .append(
                response.data.map(createComponent)
            )

    }).catch(function (error) {
        __.toast(error.message || "Something went wrong", 5, 'text-danger');
    });

    function createComponent(item) {
        return $(`<li class="list-group-item position-relative">`)
            .append(
                $(`<div class="d-flex justify-content-start align-items-center">`)
                    .append(
                        icons[item.type],
                        $(`<div class="ms-3">`)
                            .append(
                                $(`<h5 class="mb-1" title="${item.is_default ? 'Default contact' : ''}">`).append(item.name)
                                    .append(
                                        item.is_default
                                            ? $(`<i class="fa-solid fa-star ms-1 text-warning"></i>`)
                                            : ''
                                    ),
                                $(`<p class="mb-0 text-muted">`).append(item.address).css({ whiteSpace: "nowrap", overflow: "hidden", textOverflow: "ellipsis", fontSize: "0.8rem" })
                            )
                    )
            )
            .append(
                $(`<div class="dropdown position-absolute top-0 end-0">`)
                    .append(
                        $(`<button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">`),
                        $(`<ul class="dropdown-menu dropdown-menu-end">`)
                            .append(
                                $(`<li class="dropdown-item" type="button">`).append("Edit")
                                    .on('click', () => contact.edit(item)),
                                $(`<li class="dropdown-item text-danger" type="button">`).append("Delete")
                                    .on('click', () => contact.delete(item)),
                                $(`<li class="dropdown-item" type="button">`).append("Set as default")
                                    .on('click', () => contact.default(item))
                            )
                    )
            )
    }
}


contact.add = function () {

    const modal = $("#modal-add-contact").length ? __.modal.from($("#modal-add-contact")) : __.modal.create("Add Contact", modalContent.clone());
    $(modal.el).prop('id', 'modal-add-contact');
    $(modal.el).find('#type').on('change', function () {
        const type = $(this).val();
        const $address = $(modal.el).find('#address');
        $address.parent("label").text(labels[type] || 'Address');
        $address.prop('placeholder', placeholders[type] || 'Enter address');
        $address.prop('pattern', pattern[type] || '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$');
        $address.parent("div").find('.invalid-feedback').text(invalidMessage[type] || 'Invalid address');
        $address.trigger('input');
    });
    $(modal.el).find('#type').trigger('change');

    modal.show();

    modal.action.positive = {
        text: "add contact",
        callback: function () {
            const form = modal.el.find('form').get(0);
            if (!form.checkValidity()) {
                form.reportValidity();
                __.toast("Invalid form", 5, 'text-danger');
                return;
            }

            const type = $(form).find('#type').val();
            const name = $(form).find('#name').val();
            const address = $(form).find('#address').val();
            contact.fire('add', { name, address, type })
                ?.then(function () {
                    modal.hide();
                    contact.render();
                    form.reset();
                })
                ?.catch(function (error) {
                    __.toast(error.message || "Something went wrong", 5, 'text-danger');
                });

        }
    }
}


contact.edit = function (data) {

    const modal = $("#modal-edit-contact").length ? __.modal.from($("#modal-edit-contact")) : __.modal.create("Edit Contact", modalContent.clone());
    $(modal.el).prop('id', 'modal-edit-contact');
    $(modal.el).find('#type').on('change', function () {
        const type = $(this).val();
        const $address = $(modal.el).find('#address');
        $address.parent("label").text(labels[type] || 'Address');
        $address.prop('placeholder', placeholders[type] || 'Enter address');
        $address.prop('pattern', pattern[type] || '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$');
        $address.parent("div").find('.invalid-feedback').text(invalidMessage[type] || 'Invalid address');
        $address.trigger('input');
    });
    $(modal.el).find('#name').val(data.name);
    $(modal.el).find('#type').val(data.type).trigger('change');
    $(modal.el).find('#address').val(data.address);
    modal.show();

    modal.action.positive = {
        text: "update contact",
        callback: () => {
            const form = modal.el.find('form').get(0);
            if (!form.checkValidity()) {
                form.reportValidity();
                __.toast("Invalid form", 5, 'text-danger');
                return;
            }

            const type = $(form).find('#type').val();
            const name = $(form).find('#name').val();
            const address = $(form).find('#address').val();

            contact.fire('update', { name, address, type, id: data.id })
                ?.then(function () {
                    modal.hide();
                    contact.render();
                    form.reset();
                })
                ?.catch(function (error) {
                    __.toast(error.message || "Something went wrong", 5, 'text-danger');
                });
        }
    }

}


contact.delete = function (data) {

    const content = `Are you sure you want to delete <b>${data.name}</b>?`;

    __.dialog.danger("Are you sure ?", content)
        .then(function () {
            contact.fire('delete', data)
                ?.then(function () {
                    contact.render();
                })
                ?.catch(function (error) {
                    __.toast(error.message || "Something went wrong", 5, 'text-danger');
                });
        })

}

contact.default = function (data) {
    contact.fire('default', data)
        ?.then(function () {
            contact.render();
        })
        ?.catch(function (error) {
            __.toast(error.message || "Something went wrong", 5, 'text-danger');
        });
}