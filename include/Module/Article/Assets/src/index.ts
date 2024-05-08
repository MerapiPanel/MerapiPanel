import { ModalTypeInterface } from "@il4mb/merapipanel/modal";
import { __MP } from "../../../../Buildin/src/main";

const __: __MP = (window as any).__;
const Article = __.Article;

type EndpointsType = {
    createURL: string
    editURL: string
    view: string
    save: string
    update: string
    delete: string
}

Article.endpoints = {} as EndpointsType;
const endpoints = Article.endpoints;
const articles: any[] = ((window as any).articles || []);
const updateEndpoint = endpoints.update;
const deleteEndpoint = endpoints.delete;
const viewEndpoint = endpoints.view;
const editURL = endpoints.editURL;

(window as any).render = (container) => {
    setTimeout(() => {
        if (articles.length > 0) {
            $(container).html("").append(articles.map((article) => createCard(article, container)) as JQuery<HTMLElement>[]);
        } else {
            $(container).html("").append($(`<div class='text-muted text-center w-100 py-5 my-5 fs-2'>No articles found</div>`));
        }
    }, 600);
}

function render(container: any) {
    (window as any).render(container);
}

function createCard(article: any, container: any): JQuery<HTMLElement> {

    const card = $("<div class='card card-body border-0 shadow-sm d-flex flex-column position-relative' style='width: 100%; max-width: 30rem;'>")
        .append(
            $(`<div class='flex-grow-1'>`)
                .append(
                    $(`<div class='d-flex'>`)
                        .append(article.status ? '<i class="fa-solid fa-earth-americas"></i>' : '<i class="fa-solid fa-lock"></i>')
                        .append($(`<h5 class="ms-1 card-title">${article.title || 'No Title'}</h5>`))
                )
                .append(`<p>${article.description || '<span class="text-muted opacity-50">No Description</span>'}</p>`)
        )
        .append(
            $("<div>")
                .append(
                    $(`<button class='btn btn-sm btn-primary px-5 mb-2'>View</button>`)
                        .on('click', () => {
                            if (viewEndpoint) {
                                window.open(viewEndpoint.replace('{id}', article.id), '_blank');
                            } else {
                                __.toast("No view endpoint found", 5, 'text-danger');
                            }
                        })
                )
                .append(
                    $(`<div class='d-flex gap-3 text-muted'>`)
                        .append(`<span>Update: ${article.update_date || 'No Date'}</span>`)
                        .append(`<span class='ms-auto'>${article.author_name || 'No Author'}</span>`)
                )
        )
        .append(
            $(`<div class='dropdown position-absolute top-0 end-0'>`)
                .append(
                    $(`<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">`)
                        .append(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">`)
                )
                .append(
                    $(`<div class="dropdown-menu dropdown-menu-end">`)
                        .append(
                            $(`<a class="dropdown-item" href="#">Edit</a>`)
                                .on('click', () => {
                                    if (editURL) {
                                        window.location.href = editURL.replace('{id}', article.id);
                                    } else {
                                        __.toast("No edit URL found", 5, 'text-danger');
                                    }
                                })
                        )
                        .append(
                            $(`<a class="dropdown-item" href="#">Quick Edit</a>`)
                                .on('click', () => QuickEdit(article, container))
                        )
                        .append(
                            $(`<a class="dropdown-item text-danger" href="#">Delete</a>`)
                                .on('click', () => ConfirmDelete(article, container))
                        )
                )
        )


    return card;
}


function QuickEdit(article: any, container: any) {

    let modal: ModalTypeInterface | null = null;

    if ($('#quick-edit-modal').length > 0) {
        modal = __.modal.from($('#quick-edit-modal'));
    } else {
        modal = __.modal.create("Edit Article", "");
        modal.el.attr('id', 'quick-edit-modal');
    }
    modal.dismiss = null;
    modal.clickOut = false;

    modal.action.negative = () => {
        modal.hide();
    }

    const form: JQuery<HTMLFormElement> = $(`<form class="needs-validation">`)
        .append(
            $(`<div>`)
                .append(
                    $(`<label class='mb-3 d-block'>`)
                        .append("Title : ")
                        .append(`<input type="text" class="form-control" name="title" pattern="[a-zA-Z\\s]{5,}" value="${article.title || ''}">`)
                        .append(`<div class="invalid-feedback">Please enter a title</div>`)
                )
                .append(
                    $(`<label class='mb-3 d-block'>`)
                        .append("Slug : ")
                        .append(`<input type="text" class="form-control" name="slug" value="${article.slug || ''}">`)
                        .append(`<div class="invalid-feedback">Please enter a slug</div>`)
                )
                .append(
                    $(`<label class='mb-3 d-block'>`)
                        .append("Keywords : ")
                        .append(`<input type="text" class="form-control" name="keywords" value="${article.keywords || ''}">`)
                        .append(`<div class="invalid-feedback">Please enter a keywords</div>`)
                )
                .append(
                    $(`<label class='mb-3 d-block'>`)
                        .append("Description : ")
                        .append(`<textarea class="form-control" name="description">${article.description || ''}</textarea>`)
                        .append(`<div class="invalid-feedback">Please enter a description</div>`)
                )
                .append(
                    $(`<label class='mb-3 d-block'>`)
                        .append("Category : ")
                        .append(`<input type="text" class="form-control" name="category" value="${article.category || ''}">`)
                        .append(`<div class="invalid-feedback">Please enter a category</div>`)
                )
                .append(
                    $(`<label class='mb-3 d-block form-check'>`)
                        .append(`<input class='form-check-input' type="checkbox" name="status" ${article.status ? 'checked' : ''}>`)
                        .append(`<span class='form-check-label'>Publish</span>`)
                )
        ) as any;
    modal.content = form;
    modal.show();

    modal.action.positive = () => {
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        let found_article = articles.find((a: any) => a.id === article.id);
        found_article = {
            ...article,
            ...form.serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                if (item.name === 'status') obj[item.name] = item.value === 'on' ? 1 : 0;
                return obj;
            }, { status: 0 })
        }

        Object.keys(articles[articles.indexOf(article)]).forEach((key) => {
            articles[articles.indexOf(article)][key] = found_article[key];
        })
        render(container);

        if (!updateEndpoint) {
            __.toast("No update endpoint found", 5, 'text-danger');
        }

        __.http.post(updateEndpoint, found_article).then((res) => {
            if (res.code === 200) {
                __.toast("Article updated", 5, 'text-success');
                // modal.hide();
            } else {
                __.toast(res.message || "Failed to update article", 5, 'text-danger');
            }
        }).catch(err => {
            __.toast(err.message || "Failed to update article", 5, 'text-danger');
        })
    }

}

function ConfirmDelete(article: any, container: any) {
    __.dialog.danger("Are you sure?",
        $(`<div>`)
            .append(
                $(`<div>Are you sure you want to delete this article?</div>`)
                    .append(
                        $(`<div class='py-3'>`)
                            .append(`<div class='fw-bold'>${article.title}</div>`)
                            .append(`<div class='text-primary text-opacity-75'>${article.id}</div>`)
                    )
            )
            .append(`<p class='text-danger'>This action cannot be undone.</p>`)
    )
        .then(() => {
            if (!deleteEndpoint) {
                __.toast("No delete endpoint found", 5, 'text-danger');
                return;
            }

            __.http.post(deleteEndpoint, { id: article.id }).then((res) => {

                if (res.code == 200) {
                    __.toast("Article deleted", 5, 'text-success');
                    let key = articles.indexOf(article);
                    const l = articles.length;
                    articles.splice(key, 1);
                    if (articles.length < l) {
                        render(container);
                    } else {
                        window.location.reload();
                    }
                } else {
                    __.toast(res.message || "Failed to delete article", 5, 'text-danger');
                }
            }).catch(err => {
                __.toast(err.message || "Failed to delete article", 5, 'text-danger');
            })
        })
        .catch(() => {
            __.toast("Action cancelled", 5, 'text-info');
        })
}