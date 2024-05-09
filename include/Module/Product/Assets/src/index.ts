import { __MP } from "../../../../Buildin/src/main"
import { Component } from "grapesjs";

const __: __MP = (window as any).__;

type MProductType = {
    endpoints: {
        fetchAll: string
        fetch: string
        view: string
        update: string
        delete: string
        edit: string
    }
    config: {
        payload: {
            page: number
            limit: number
            search: string
        }
        [key: string]: any
    }
    render: Function
    el: HTMLElement
    [key: string]: any
}
const MProduct: MProductType = __.MProduct;

if (!MProduct.config) {
    MProduct.config = {
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
if (!MProduct.config.roles) {
    MProduct.config.roles = {}
}
if (!MProduct.config.payload) {
    MProduct.config.payload = {
        page: 1,
        limit: 10,
        search: ""
    }
}
const roles = {
    ...{
        modify: true
    },
    ...MProduct.config.roles
}
const payload = MProduct.config.payload;

$("#panel-subheader-search").on("submit", function (e) {
    e.preventDefault();
    payload.page = 1;
    payload.search = $(this).find("input").val() as string;
    MProduct.render();
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
        MProduct.render();
    });
}

MProduct.render = function () {

    if (this instanceof HTMLElement) {
        MProduct.el = this;
    }

    __.http.get(MProduct.endpoints.fetchAll, payload)
        .then(function (response) {
            const { products, totalResults, totalPages } = response.data as any;
            if (payload.page > totalPages) {
                payload.page = totalPages;
                return MProduct.render();
            }
            $("#total-records").text(`Total Records: ${totalResults}`);
            createPagination($("#pagination"), payload.page, totalPages);

            setTimeout(() => {

                $(MProduct.el).html("").append((products || []).map(createCard));
                if ((products || []).length === 0) {
                    $(MProduct.el)
                        .html("")
                        .css({
                            position: 'relative',
                            flex: '1'
                        })
                        .append(
                            $("<div>No data found</div>")
                                .css({
                                    position: "absolute",
                                    top: "50%",
                                    left: "50%",
                                    transform: "translate(-50%, -50%)",
                                    textAlign: "center",
                                })
                        )
                }

            }, 400);
        })
        .catch(error => {
            __.toast(error instanceof String ? error : (error.message || "Error on rendering data"), 5, 'text-danger');
        });
}

type Product = {
    id: string
    title: string
    price: string | number
    category: string
    description: string
    data: {
        components: Component
        css: string
    }
    images?: string[]
    status: number
    post_date: string
    update_date: string
    author_id: string
    author_name: string

}



function createCard(product: Product): JQuery<HTMLElement> {

    const config = { currency: "USD", currency_symbol: "$", ...(MProduct.config || {}) };
    const content = $("<div class='card position-relative w-100' style='max-width: 12rem;'>")
        .css("min-width", window.innerWidth < 576 ? "12rem" : "18rem")
        .append(
            product.images
                ? $(`<div id='carousel-${product.id}' class="carousel slide carousel-fade" data-bs-ride="carousel" onload="this.init()">`)
                    .append(
                        $("<div class='carousel-inner'>")
                            .append(product.images.map((image, i) =>
                                $(`<div class='carousel-item${i === 0 ? ' active' : ''}' data-bs-interval="${Math.random() * (6000 - 3000) + 3000}">`)
                                    .append($(`<img class='d-block w-100' src='${image}' style='height: 200px;object-fit: cover;'>`))))
                    )
                : $("<div class='carousel-item'>")
                    .append($(`<div>No images</div>`))
        )
        .append(
            $("<div class='card-body'>")
                .append($(`<h5 class='card-title'>${product.title}</h5>`))
                .append($(`<p class='card-text'>${(product.description || "").length > 100 ? (product.description || "").slice(0, 75) + "..." : product.description}</p>`))
                .append($(`<div><span class='fw-semibold'>${config.currency_symbol} ${product.price}</span> | <i class='fw-light'>${product.category}</i></div>`))
        )
        .append(
            (roles.modify == true) ?
                $(`<div class='dropdown position-absolute end-0 top-0' style='z-index: 1;' role='dropdown'>`)
                    .append("<button class='btn dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button>")
                    .append(
                        $(`<div class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>`)
                            .append($(`<a class='dropdown-item' href='#'>View</a>`).on('click', () => actionView(product)))
                            .append($(`<a class='dropdown-item' href='#'>Quick Edit</a>`).on('click', () => actionQuickEdit(product)))
                            .append($(`<a class='dropdown-item' href='#'>Edit</a>`).on('click', () => actionEdit(product)))
                            .append($(`<a class='dropdown-item' href='#'>Delete</a>`).on('click', () => actionDelete(product)))
                    ) : ""
        );


    try {
        (content.find('.carousel')[0] as any).init = function () {
            if ((window as any).bootstrap) {
                const carousel = new (window as any).bootstrap.Carousel(this);
                $(this).on('destroyed', () => carousel.dispose())
            }
        }
    } catch (error) {
        console.log(error)
    }

    return content;
}

function actionView(product: Product): void {
    window.location.href = __.endpoints.view.replace("{id}", product.id);
}

function actionQuickEdit(product: Product): void {

    const content = $("<form class='row g-3 needs-validation'>")
        .append(
            $("<div class='form-group'>")
                .append($("<label class='form-label'>Title</label>"))
                .append(`<input type='text' class='form-control' name='title' placeholder='Enter title' value='${product.title}' pattern='[a-zA-Z0-9\\s]{3,255}' required>`)
                .append($("<div class='invalid-feedback'>Please provide a valid title, at least 3 characters.</div>"))
                .append($("<div class='valid-feedback'>Looks good!</div>")),
            $("<div class='form-group'>")
                .append($("<label class='form-label'>Price</label>"))
                .append(
                    $(`<input type='text' class='form-control' name='price' placeholder='Enter price' inputmode='numeric' pattern='[0-9.]*' maxlength='24' value='${product.price}'>`)
                        .on('input', function () {
                            $(this).val(($(this).val() as any || "").replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
                        }),

                )
                .append($("<div class='invalid-feedback'>Please enter number only.</div>"))
                .append($("<div class='valid-feedback'>Looks good!</div>")),
            $("<div class='form-group'>")
                .append($("<label class='form-label'>Category</label>"))
                .append(`<input type='text' class='form-control' name='category' placeholder='Enter category' pattern='[a-zA-Z]{3,24}[a-zA-Z0-9\\s]{0,24}' required value='${product.category}'>`)
                .append(`<div class='invalid-feedback'>Please provide a valid category, at least 3 characters.</div>`)
                .append(`<div class='valid-feedback'>Looks good!</div>`),
            $("<div class='form-group'>")
                .append($("<label class='form-label'>Description</label>"))
                .append(
                    $(`<textarea class='form-control' name='description' placeholder='Enter description' rows='3' minlength='30' maxlength='255' required>${product.description}</textarea>`)
                        .on('input', function () {
                            $(this).parent(".form-group").find(".length").text(($(this).val() as any).length + "/255");
                        })
                )
                .append(`<small class='length'>${product.description.length}/255</small>`)
                .append(`<div class='valid-feedback'>Looks good!</div>`),
            $(`<div class='form-group'>`)
                .append($("<label class='form-label'>Status</label>"))
                .append(
                    $(`<select class='form-select' name='status'>`)
                        .append(
                            $(`<option value='1' ${product.status === 1 ? 'selected' : ''}>Publish</option>`),
                            $(`<option value='0' ${product.status === 0 ? 'selected' : ''}>Unpublish</option>`)
                        )
                )
        )

    const modal = $("#modal-quick-edit").length ? __.modal.from($("#modal-quick-edit")) : __.modal.create("Quick Edit", null);

    modal.content = content;
    modal.show();

    modal.action.positive = function () {
        if ((content[0] as HTMLFormElement).checkValidity()) {
            __.http.post(MProduct.endpoints.update, {
                id: product.id,
                title: content.find("input[name='title']").val(),
                price: content.find("input[name='price']").val(),
                category: content.find("input[name='category']").val(),
                description: content.find("textarea[name='description']").val(),
                status: content.find("select[name='status']").val()
            }).then((res) => {
                if (res.code === 200) {
                    __.toast("Product updated", 5, "text-success");
                    MProduct.render();
                } else throw new Error(res.message);
            }).catch((err) => {
                __.toast(err.message || "Failed to update product", 5, "text-danger");
            })
        } else {
            __.toast("Please check your form", 5, "text-danger");
        }
    }
}


function actionEdit(product: Product): void {

    window.location.href = MProduct.endpoints.edit.replace("{id}", product.id);

}

function actionDelete(product: Product): void {
    const content = $("<div>")
        .append("<p>Are you sure want to delete this product?</p>")
        .append("<p>Details as below</p>")
        .append(
            $("<table class='table table-bordered'>")
                .append(
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>id</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.id || ""}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>title</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.title || ""}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>price</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.price || 0}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>category</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.category || ''}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>description</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.description || ""}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>status</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.status === 1 ? "Publish" : "Unpublish"}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>post_date</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.post_date || "-"}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>update_date</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.update_date || "-"}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>author_id</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.author_id || "-"}</p></td>`),
                    $("<tr style='vertical-align: baseline;'>")
                        .append("<td>author_name</td>")
                        .append(`<td><p style="word-break: break-all; margin:0;">${product.author_name || "-"}</p></td>`)
                )
        )


    __.dialog.danger("Are you sure?", content)
        .then(function () {
            __.http.post(MProduct.endpoints.delete, { id: product.id }).then((res) => {
                if (res.code === 200) {
                    __.toast("Success delete product", 5, "text-success");
                    MProduct.render();
                } else throw new Error(res.message);
            }).catch(() => {
                __.toast("Failed to delete product", 5, "text-danger");
            })
        }).catch(() => {
            __.toast("Action cancelled", 5, "text-info");
        })
}

