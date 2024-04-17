import { Modal } from "@il4mb/merapipanel/modal";
import * as http from "@il4mb/merapipanel/http";
import * as dialog from "@il4mb/merapipanel/dialog";
import { toast } from "@il4mb/merapipanel/toast";


const { urls, session } = __.settings;

__.render = function () {

    if (!__.container && this instanceof HTMLElement) {
        __.container = this;
    }

    http.get(urls.fetch).then(function (result) {
        $(__.container).html("").append(result.data.map((user) => createComponent(user)));
    });
}



function createComponent(user) {

    return $(`<li class="list-group-item d-flex align-items-start position-relative">`)
        .append(`<img class="w-full h-full object-cover rounded-2" width="45" height="45" src="${user.avatar ? user.avatar : ''}" alt="${user.name}">`)
        .append(
            $(`<div class="ms-2 w-100">`)
                .append(`<div class="fw-bold">${user.name} ${user.email == session.email ? '<small><i>( You )</i></small>' : ''}</div>`)
                .append(`<div class='d-flex'>${user.email} - <i>${user.role}</i><span class="badge badge-sm bg-${['danger', 'warning', 'primary'][user.status]} ms-2">${['Inactive', 'Suspended', 'Active'][user.status]}</span></div></div>`)
        )
        .append(
            $(`<div class="dropdown position-absolute top-0 end-0">`)
                .append(`<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>`)
                .append(
                    $(`<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">`)
                        .append(
                            $(`<li><a class="dropdown-item text-primary" href="#">Edit</a></li>`)
                                .on('click', () => editUser(user))
                        )
                        .append(
                            user.status === 2 ?
                                $(`<li><a class="dropdown-item  text-warning" href="#">Suspend</a></li>`)
                                    .on('click', () => suspendUser(user))
                                : user.status === 1 ?
                                    $(`<li><a class="dropdown-item  text-success" href="#">Unsuspend</a></li>`)
                                        .on('click', () => unsuspendUser(user))
                                    : ''
                        )
                        .append(
                            $(`<li><a class="dropdown-item bg-danger bg-opacity-10 text-danger" href="#">Delete</a></li>`)
                                .on('click', () => deleteUser(user))
                        )
                )
        )
}



function editUser(user) {

    const content = $(`<form class="needs-validation">`)
        .append(`<input type="hidden" name="id" value="${user.id}" readonly>`)
        .append(
            $(`<div class="mb-3">`)
                .append(`<label for="name" class="form-label">Name</label>`)
                .append(`<input type="text" class="form-control" name="name" placeholder="Enter name" pattern="[a-zA-Z ]+" id="name" value="${user.name}" required>`)
                .append(`<div class="valid-feedback">Looks good!</div>`)
                .append(`<div class="invalid-feedback">Please enter a valid name.</div>`)
        )
        .append(
            $(`<div class="mb-3">`)
                .append(`<label for="email" class="form-label">Email</label>`)
                .append(`<input type="email" class="form-control" id="email" name="email" placeholder="Enter email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$" value="${user.email}" required>`)
                .append(`<div class="valid-feedback">Looks good!</div>`)
                .append(`<div class="invalid-feedback">Please enter a valid email address.</div>`)
        )
        .append(
            user.role && $(`<div class="mb-3">`)
                .append(`<label for="role" class="form-label">Role</label>`)
                .append(
                    $(`<select class="form-select" name="role" id="role" required>`)
                        .append(
                            ['admin', 'editor', 'contributor', 'manager', 'moderator'].map((role) => `<option value="${role}" ${user.role == role ? 'selected' : ''}>${role}</option>`)
                        )
                )
        )
        .append(
            user.status !== undefined && $(`<div class="mb-3">`)
                .append(`<label for="status" class="form-label">Status</label>`)
                .append(
                    $(`<select class="form-select" name="status" id="status" required>`)
                        .append(
                            ['unactive', 'suspended', 'active'].map((status, index) => `<option value="${index}" ${user.status == index ? 'selected' : ''}>${status}</option>`)
                        )
                )
        )
        .append(
            $('<div class="mb-3">')
                .append(
                    $(`<div class='form-check'>`)
                        .append(`<label class="form-check-label" for="change-password">Change Password</label>`)
                        .append(
                            $(`<input class="form-check-input" type="checkbox" id="change-password" name="change-password">`).on('change', function () {

                                if (this.checked) {
                                    $('#password-container').removeClass('d-none');
                                    $('#password').prop({ required: true, value: "", disabled: false, readonly: false });
                                    $('#confirm-password').prop({ required: true, value: "", disabled: false, readonly: false });
                                } else {
                                    $('#password-container').addClass('d-none');
                                    $('#password').prop({ required: false, value: "", disabled: true, readonly: true });
                                    $('#confirm-password').prop({ required: false, value: "", disabled: true, readonly: true });
                                }
                            }))

                )
                .append(
                    $(`<div class="mb-3 mt-2 d-none" id="password-container">`)
                        .append(
                            $(`<div class="mb-3">`)
                                .append(`<label for="password" class="form-label">Password</label>`)
                                .append(`<input type="password" name="password" placeholder="Enter password" pattern="(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" class="form-control" id="password" disabled="true">`)
                                .append(`<div class="valid-feedback">Looks good!</div>`)
                                .append(`<div class="invalid-feedback">Password must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters</div>`)
                        )
                        .append(
                            $(`<div class="mb-3">`)
                                .append(`<label for="confirm-password" class="form-label">Confirm Password</label>`)
                                .append(
                                    $(`<input type="password" name="confirm-password" class="form-control" id="confirm-password" disabled="true">`)
                                        .on('input', function () {
                                            setTimeout(() => {
                                                if ($(this).val() !== $('#password').val()) {
                                                    $(this).addClass('is-invalid');
                                                } else {
                                                    $(this).removeClass('is-invalid');
                                                }
                                            }, 100);
                                        })
                                )
                                .append(`<div class="valid-feedback d-none">Looks good!</div>`)
                                .append(`<div class="invalid-feedback d-none">Please enter the same password as the first password</div>`)
                        )
                )
        )



    const modal = $("#modal-edit-user").length > 0 ? Modal.from($("#modal-edit-user")) : Modal.create("Edit User", content);
    modal.el.attr("id", "modal-edit-user");
    modal.content = content;
    modal.show();


    modal.action.positive = function () {


        const form = $(modal.el.find('form'));

        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            toast('Please enter valid data', 5, 'text-warning');
            return;
        }

        if (form.find('[name="change-password"]').is(':checked')) {
            const password = form.find('[name="password"]').val();
            const confirmPassword = form.find('[name="confirm-password"]').val();
            if (password.length < 6 || password !== confirmPassword) {
                toast('Password and confirm password must be same', 5, 'text-warning');
                form.find('[name="confirm-password"]').addClass('is-invalid');
                form.find('[name="confirm-password"]').trigger('focus');
                return;
            }
        }

        const formData = new FormData(form[0]);
        const dialog_content = $("<div class='w-100 d-block'>");

        dialog_content.append(
            $("<div class='row border-bottom mb-1'>")
                .append("<div class='col-6 fw-semibold'>Old</div>")
                .append("<div class='col-6 fw-semibold'>New</div>")
        )

        const resolves = {
            status: [
                'unactive',
                'suspended',
                'active'
            ]
        }


        if (formData.get('change-password')) {
            formData.delete('change-password');
        }


        for (const [key, value] of formData.entries()) {

            if (key === 'confirm-password') continue;
            dialog_content.append(
                $("<div class='row'>")
                    .append(`<div class='col-6 text-muted'>${resolves[key] ? resolves[key][user[key]] : (user[key] || key)}</div>`)
                    .append(`<div class='col-6'>${!key.includes("password") ? (resolves[key] ? resolves[key][value] : value) : value.split('').map(() => '*').join('')}</div>`)
            )
        }

        dialog.confirm("Confirm Change ?", dialog_content).then(() => {

            http.post(urls.update, formData).then((result) => {
                if (result.code === 200) {

                    toast('User updated successfully', 5, 'text-success');
                    __.render();
                    // modal.hide();

                } else {
                    throw new Error(result.message);
                }
            }).catch((error) => {
                toast(error.message || error.statusText || error, 5, 'text-danger');
            })

        }).catch(() => {
            toast('Action Canceled', 5, 'text-warning');
        })
    }

}



function deleteUser(user) {

    dialog.danger("Are you sure?",
        $(`<div>Delete <b>${user.name}</b>?</div>`)
            .append("<p>Are you sure you want to delete user details as shown below?</p>")
            .append(
                $(`<table class="table table-bordered">`)
                    .append(
                        $(`<tr>`)
                            .append(`<th>id</th>`)
                            .append(`<td>${user.id}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Email</th>`)
                            .append(`<td>${user.email}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Role</th>`)
                            .append(`<td>${user.role}</td>`)
                    )
            )
            .append(`<p class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> This action cannot be undone.</p>`)
    )
        .then(() => {
            http.post(urls.delete, { id: user.id }).then((result) => {
                if (result.code === 200) {
                    toast('User deleted successfully', 5, 'text-success');
                    __.render();
                } else {
                    throw new Error(result.message);
                }
            }).catch((error) => {
                toast(error.message || error.statusText || error, 5, 'text-danger');
            })
        })
        .catch(() => {
            toast('Action Canceled', 5, 'text-warning');
        })
}


function suspendUser(user) {

    dialog.warning("Are you sure?",
        $(`<div>Suspend <b>${user.name}</b>?</div>`)
            .append("<p>Are you sure you want to suspend user details as shown below?</p>")
            .append(
                $(`<table class="table table-bordered">`)
                    .append(
                        $(`<tr>`)
                            .append(`<th>id</th>`)
                            .append(`<td>${user.id}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Email</th>`)
                            .append(`<td>${user.email}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Role</th>`)
                            .append(`<td>${user.role}</td>`)
                    )
            )
    )
        .then(() => {

            http.post(urls.update, { status: 1, id: user.id }).then((result) => {

                if (result.code === 200) {
                    toast('User suspended successfully', 5, 'text-success');
                    __.render();
                } else {
                    throw new Error(result.message);
                }
            }).catch((error) => {
                toast(error.message || error.statusText || error, 5, 'text-danger');
            })
        })
        .catch(() => {
            toast('Action Canceled', 5, 'text-warning');
        })
}


function unsuspendUser(user) {

    dialog.confirm("Are you sure?",
        $(`<div>Unsuspend <b>${user.name}</b>?</div>`)
            .append("<p>Are you sure you want to unsuspend user details as shown below?</p>")
            .append(
                $(`<table class="table table-bordered">`)
                    .append(
                        $(`<tr>`)
                            .append(`<th>id</th>`)
                            .append(`<td>${user.id}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Email</th>`)
                            .append(`<td>${user.email}</td>`)
                    )
                    .append(
                        $(`<tr>`)
                            .append(`<th>Role</th>`)
                            .append(`<td>${user.role}</td>`)
                    )
            )
    )
        .then(() => {

            http.post(urls.update, { status: 2, id: user.id }).then((result) => {

                if (result.code === 200) {
                    toast('User unsuspended successfully', 5, 'text-success');
                    __.render();
                } else {
                    throw new Error(result.message);
                }
            }).catch((error) => {
                toast(error.message || error.statusText || error, 5, 'text-danger');
            })
        })
        .catch(() => {
            toast('Action Canceled', 5, 'text-warning');
        })
}
