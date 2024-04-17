import * as toast from "@il4mb/merapipanel/toast";
import * as http from "@il4mb/merapipanel/http";

const form = $(`<form class="needs-validation">`)
    .append(
        window.article?.id && (
            $(`<div class="mb-3 py-2">ID : ${window.article.id}</div>`)
        )
    )
    .append(
        $(`<div class="mb-3">`)
            .append(
                $(`<label class="d-block">`)
                    .append("Enter title:")
                    .append(`<input class="form-control" type="text" name="title" placeholder="Enter title" pattern="[a-zA-Z\\s]{5,}" invalid-message="Please enter title" value="${window.article?.title || ""}">`)
            )
            .append(`<div class="invalid-feedback">Please enter title with at least 5 characters a-z</div>`)
    )
    .append(
        $(`<div class='mb-3'>`)
            .append(
                $(`<label class='d-block'>`)
                    .append("Enter slug:")
                    .append(`<input class="form-control" type="text" name="slug" placeholder="Enter slug" value="${window.article?.slug || ""}">`)
            )
    )
    .append(
        $(`<div class='mb-3'>`)
            .append(
                $(`<label class="d-block">`)
                    .append("Enter keywords:")
                    .append(`<input class="form-control" type="text" name="keywords" placeholder="Enter keywords" value="${window.article?.keywords || ""}">`)
            )
    )
    .append(
        $(`<div class='mb-3'>`)
            .append(
                $(`<label class='d-block'>`)
                    .append("Enter description:")
                    .append(`<textarea class="form-control" name="description" placeholder="Enter description">${window.article?.description || ""}</textarea>`)
            )
    )
    .append(
        $(`<div class='mb-3'>`)
            .append(
                $(`<label class='d-block'>`)
                    .append("Enter category:")
                    .append(`<input class="form-control" type="text" name="category" placeholder="Enter category" value="${window.article?.category || ''}">`)
            )
    )
    .append(
        $(`<div class='mb-3'>`)
            .append(
                $(`<label class='d-block form-check'>`)
                    .append(`<input class='form-check-input' type="checkbox" name="status" ${window.article?.status ? 'checked' : ''}>`)
                    .append(`<span class='form-check-label'>Publish</span>`)
            )
    )
    .append(
        $('<div class="mb-3 d-flex">')
            .append('<button type="submit" class="ms-auto btn btn-primary">Submit</button>')
    )



$(form).find('[name="title"]').on('input', function () {
    $(form).find('[name="slug"]').val($(this).val().split(/\s+/).filter((w) => w.length > 0).join('-').toLowerCase());
});



editor.callback = function (data) {

    const editor = this.editor;
    const modal = editor.Modal;
    let isComplete = false;

    if (!saveEndpoint) {
        toast("Please set save endpoint", 5, "text-danger");
        return;
    }

    modal.open({
        title: 'Article Meta Data',
        attributes: { class: 'my-class' },
    });

    modal.setContent($(`<div class="form-article">`).append(form));

    modal.onceClose(() => {
        if (!isComplete) this.reject("Action canceled");
    });

    form.off('submit'); // prevent double submit

    form.on('submit', (e) => {
        e.preventDefault();
        console.clear();
        if (!form[0].checkValidity()) {
            toast("Please enter valid data", 5, "text-danger");
            return;
        }

        isComplete = true;
        editor.Modal.close();

        const title = form.find('[name="title"]').val();
        const slug = form.find('[name="slug"]').val();
        const keywords = form.find('[name="keywords"]').val();
        const category = form.find('[name="category"]').val();
        const description = form.find('[name="description"]').val();
        const status = form.find('[name="status"]').is(':checked') ? 1 : 0;

        http.post(saveEndpoint, Object.assign((window.article?.id ? { id: window.article?.id } : {}), {
            title,
            slug,
            keywords,
            category,
            description,
            data: JSON.stringify(this.data),
            status,
        })).then((response) => {

            if (window.article) {
                window.article.id = response.data.id;
            } else {
                window.article = response.data;
            }

            toast(response.message ?? "Saved", 5, 'text-success');
            if (window.history.replaceState && window.article?.settings?.prefix && window.article?.settings?.path_edit) {
                const target = window.article.settings.prefix + window.article.settings.path_edit.replace("{id}", response.data.id);

                window.history.replaceState(null, null, target);
            }

            this.resolve(response);

        }).catch((err) => this.reject(err))
    });
}


