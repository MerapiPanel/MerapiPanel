import { ModalTypeInterface } from "@il4mb/merapipanel/modal";
import { __MP } from "../../../../Buildin/src/main";

const __: __MP = (window as any).__;
const MArticle = __.Article;
type EndpointsType = {
    create: string
    edit: string
    fetchAll: string
    view: string
    save: string
    update: string
    delete: string
}

if (!MArticle.config) {
    MArticle.config = {
        payload: {
            page: 1,
            limit: 10,
            search: ""
        },
        roles: {
            modify: true
        }
    }
}
if (!MArticle.config.roles) {
    MArticle.config.roles = {}
}
if (!MArticle.config.payload) {
    MArticle.config.payload = {
        page: 1,
        limit: 10,
        search: ""
    }
}


const roles = {
    ...{
        modify: true
    },
    ...MArticle.config.roles
}
const endpoints: EndpointsType = MArticle.endpoints;
const payload = MArticle.config.payload;

$("#panel-subheader-search").on("submit", function (e) {
    e.preventDefault();
    payload.page = 1;
    payload.search = $(this).find("input").val() as string;
    MArticle.render();
});


function createPagination(container: HTMLElement | JQuery, page: number, totalPages: number) {

    $(container).html("");
    // Define the number of pages to show before and after the current page
    const range = 3;

    // Determine start and end points for the pagination range
    let start = Math.max(1, page - range);
    let end = Math.min(totalPages, page + range);

    // Adjust start and end points if necessary to ensure that range remains constant
    if (end - start < 2 * range) {
        if (start === 1) {
            end = Math.min(totalPages, start + 2 * range);
        } else if (end === totalPages) {
            start = Math.max(1, end - 2 * range);
        }
    }

    // Add left offset if not on the first page
    if (page > 1) {
        $(container).append('<li class="page-item"><a class="page-link" href="#" data-page="1">&laquo;</a></li>');
    }

    // Add page links
    for (let i = start; i <= end; i++) {
        const liClass = i === page ? "page-item active" : "page-item";
        $(container).append($('<li class="' + liClass + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>'));
    }

    // Add right offset if not on the last page
    if (page < totalPages) {
        $(container).append('<li class="page-item"><a class="page-link" href="#" data-page="' + totalPages + '">&raquo;</a></li>');
    }

    // Add event listeners for page links
    $(container).find("a.page-link").on("click", function (e) {
        e.preventDefault();
        payload.page = parseInt($(this).data("page"));
        MArticle.render();
    });
}

MArticle.render = function () {

    if (this instanceof HTMLElement) {
        MArticle.el = this;
    }

    if (!endpoints.fetchAll) {
        throw new Error('Please provide an endpoint to fetch all articles');
    }

    __.http.get(endpoints.fetchAll, payload)
        .then((res) => {
            const { articles, totalPages, totalResults } = res.data as any;
            if (payload.page > totalPages) {
                payload.page = totalPages;
                return MArticle.render();
            }
            $("#total-records").text(`Total Records: ${totalResults}`);
            createPagination($("#pagination"), payload.page, totalPages);
            setTimeout(() => {
                if ((articles || []).length > 0) {
                    $(MArticle.el).html("").append((articles || []).map((article: any) => createCard(article)) as JQuery<HTMLElement>[]);
                } else {
                    $(MArticle.el).html("").append($(`<div class='text-muted text-center w-100 py-5 my-5 fs-2'>No articles found</div>`));
                }
            }, 600);
        })
        .catch(err => {
            __.toast(err.message || "Something went wrong", 5, 'text-danger');
        })
}

function createCard(article: any): JQuery<HTMLElement> {

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
                            if (endpoints.view) {
                                window.location.href = endpoints.view.replace('{id}', article.id);
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
            (roles.modify == true) ?
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
                                        if (endpoints.edit) {
                                            window.location.href = endpoints.edit.replace('{id}', article.id);
                                        } else {
                                            __.toast("No edit URL found", 5, 'text-danger');
                                        }
                                    })
                            )
                            .append(
                                $(`<a class="dropdown-item" href="#">Quick Edit</a>`)
                                    .on('click', () => QuickEdit(article))
                            )
                            .append(
                                $(`<a class="dropdown-item text-danger" href="#">Delete</a>`)
                                    .on('click', () => ConfirmDelete(article))
                            )
                    ) : ''
        )


    return card;
}


function QuickEdit(article: any) {

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
        // let found_article = articles.find((a: any) => a.id === article.id);
        let update_article = {
            ...article,
            ...form.serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                if (item.name === 'status') obj[item.name] = item.value === 'on' ? 1 : 0;
                return obj;
            }, { status: 0 })
        }

        // Object.keys(articles[articles.indexOf(article)]).forEach((key) => {
        //     articles[articles.indexOf(article)][key] = found_article[key];
        // })
        // render(container);

        if (!endpoints.update) {
            __.toast("No update endpoint found", 5, 'text-danger');
        }

        __.http.post(endpoints.update, update_article).then((res) => {
            if (res.code === 200) {
                __.toast("Article updated", 5, 'text-success');
                MArticle.render();
            } else {
                __.toast(res.message || "Failed to update article", 5, 'text-danger');
            }
        }).catch(err => {
            __.toast(err.message || "Failed to update article", 5, 'text-danger');
        })
    }

}

function ConfirmDelete(article: any) {
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
            if (!endpoints.delete) {
                __.toast("No delete endpoint found", 5, 'text-danger');
                return;
            }

            __.http.post(endpoints.delete, { id: article.id }).then((res) => {

                if (res.code == 200) {
                    __.toast("Article deleted", 5, 'text-success');
                    MArticle.render();
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