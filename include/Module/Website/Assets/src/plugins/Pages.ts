import { Editor, Pages } from "grapesjs";
import { EditorView, basicSetup, minimalSetup } from 'codemirror';
import { autocompletion } from "@codemirror/autocomplete";
import { html } from '@codemirror/lang-html';
import { __MP } from "../../../../../Buildin/src/main";


const __: __MP = (window as any).__;


type Page = {
    id?: string
    name?: string
    title: string
    description: string | undefined | null
    route: string
    components: any[]
    styles: string | undefined | null
    variables: {
        name: string
        value: string
    }[]
    header?: string
    post_date?: string
    update_date?: string
    isChanged?: boolean
}

export const Register = (editor: Editor, opts: {
    appendTo?: string,
    pages?: any[]
} = {}) => {


    var customizePage: Page | null = null;


    editor.Commands.add("pages:save", {
        run: function () {
            if (customizePage) {
                customizePage.components = JSON.stringify(editor.getComponents()) as any;
                customizePage.styles = editor.getCss() as any;
                customizePage.isChanged = false;
            }
            render();
            return customizePage;
        }
    });

    editor.Commands.add("pages:customize", {

        run: function (editor, sender, opts) {

            const pageKeys = ["id", "name", "title", "description", "route", "components", "styles", "variables", "header", "post_date", "update_date", "isChanged"];
            const page = opts as Page;

            if (pageKeys.filter(key => Object.keys(page).includes(key)).length <= 3) {
                return;
            }

            customizePage = page;
            window.localStorage.setItem('customize-page', JSON.stringify(page));

            const components = typeof customizePage.components === "string" ? JSON.parse(customizePage.components) : customizePage.components || [];
            const styles = customizePage.styles || "";
            editor.setComponents(components);
            editor.setStyle(styles);
            const wrapper: any = editor.getWrapper();
            wrapper.addStyle({
                'min-height': '100vh',
                'display': 'block'
            });
            stopEdit();
            render();
        }
    });



    function renderComponent(page: Page) {

        return $(`<div class="card shadow-sm rounded-0 w-100 mb-1" aria-hidden="true">`)
            .css(customizePage && customizePage.route == page.route ? {
                'background-color': '#f8f9fa',
                'border-color': '#f8f9fa',
                'border': " 2px dashed rgb(0 127 255)",
                flexBasis: "230px"
            } : { border: 0, flexBasis: "230px" })
            .append(
                $(`<div class="card-body pb-5">`)
                    .append(
                        $(`<h5 class="card-title">${page.title.length > 35 ? page.title?.substring(0, 35) + '...' : page.title || '<span class="text-muted">No title</span>'}</h5>`)
                    )
                    .append(
                        $(`<small class="card-text d-block">${(page.description || "")?.length > 55 ? page.description?.substring(0, 55) + '...' : page.description || '<span class="text-muted">No description</span>'}</small>`)
                            .css({
                                marginBottom: '0rem',
                                fontSize: '0.7rem'
                            })
                    )
                    .append(
                        $(`<small class="card-text text-muted d-block">${window.location.origin + page.route || '<span class="text-muted">No route</span>'}</small>`)
                            .css({
                                'margin-bottom': '0rem',
                                'font-size': '0.6rem'
                            })
                    )
                    .append(

                        $(`<div class="d-flex justify-content-end position-absolute bottom-0 end-0 w-100">`)

                            .append(
                                $(`<button type="button" class="btn btn-sm text-primary border-primary rounded-0 border py-0 m-1" ${customizePage && customizePage.route == page.route ? 'disabled' : ''}><i class="fa-solid fa-paintbrush"></i></button>`)
                                    .on('click', () => customize(page)),
                                customizePage && customizePage.route == page.route
                                    ? $(`<button type="button" class="btn btn-sm text-primary border-primary rounded-0 border py-0 m-1"><i class="fa-regular fa-pen-to-square"></i></button>`)
                                        .on('click', () => startEdit(page))
                                    : (
                                        !page.name
                                            ? $(`<button type="button" class="btn btn-sm text-danger border-danger rounded-0 border py-0 m-1"><i class="fa-regular fa-trash-can"></i></button>`)
                                                .on('click', () => deletePage(page))
                                            : ""
                                    )
                            )

                    )
            )
            .append(
                page.isChanged ? $(`<span class="badge bg-warning position-absolute top-0 end-0 m-1">Changed</span>`) : ''
            )
    }


    function render() {
        const pages = opts.pages || [];
        if (opts.appendTo) {
            const container = $(opts.appendTo);

            container.html("");
            container.append(
                $(`<div class="d-flex justify-content-between w-100">`)
                    .append(
                        $(`<h5>Pages</h5>`),
                        $(`<button type="button" class="btn btn-sm text-primary border-primary rounded-0 border py-0 m-1">Add</button>`)
                            .on('click', () => addPage())
                    ),
                $(`<div class="d-flex flex-wrap flex-grow-1 gap-2">`)

                    .append(
                        pages.sort(function (a, b) { return a.route > b.route ? 1 : -1; }).map((page) => {
                            return renderComponent(page.isProxy === true ? page : wrapObject(page));
                        })
                    )
            );
        }

        function wrapObject(state: Page) {

            const oldState: any = JSON.parse(JSON.stringify(state));

            const handler: ProxyHandler<Page> = {
                get: function (obj, prop) {
                    if (prop && prop.toString().toLocaleLowerCase() == 'isproxy') return true;

                    return state[prop];
                },
                set: function (obj, prop, value) {
                    state[prop] = value;

                    if (prop == 'isChanged') {
                        return true;
                    }

                    const copy = JSON.parse(JSON.stringify(state));
                    delete copy.isChanged;

                    if (JSON.stringify(oldState).replace(/\n+|\s+/gim, "").length != JSON.stringify(copy).replace(/\n+|\s+/gim, "").length) {
                        state.isChanged = true;
                    } else {
                        state.isChanged = false;
                    }

                    render();

                    return true;
                }
            };
            return new Proxy(state, handler);
        }
    }


    if (!customizePage) {
        const storageCustomizePage = window.localStorage.getItem('customize-page');
        if (storageCustomizePage) {
            const spage = JSON.parse(storageCustomizePage);
            let find = opts.pages?.find(page => {
                let id = spage.id || null;
                let route = spage.route || null;
                if (id) {
                    return id == page.id;
                } else if (route) {
                    return route == page.route;
                }
            });

            if (find) customize(find);
            else {
                let find = opts.pages?.find(page => page.name.toLowerCase() == "index");
                if (find) customize(find);
            }
        } else {
            let find = opts.pages?.find(page => page.name.toLowerCase() == "index");
            if (find) customize(find);
        }
    }
    render();

    $(".editor-canvas-wrapper").append(
        $("<div class='page-metadata'>")
    )


    function deletePage(page: Page) {

        const index = opts.pages?.findIndex(p => p.id == page.id);
        if (!index) return;

        __.dialog.danger("Are you sure?", "Are you sure to delete this page <b>" + page.title + '</b> with id <i>' + page.id + '</i>')
            .then(() => {
                if (editor.Commands.get('pages:delete')) {
                    Promise.resolve(editor.runCommand('pages:delete', page))
                        .then(() => {
                            __.toast('Page deleted', 5, 'text-success');
                            opts.pages?.splice(index, 1);
                            render();
                        }).catch((err) => {
                            __.toast(err.message || 'Page not deleted', 5, 'text-danger');
                        });
                } else {
                    __.toast('Page not deleted', 5, 'text-danger');
                }
            })
    }

    function customize(page: Page) {
        editor.runCommand("pages:customize", page);
    }



    function addPage() {

        const page: Page = {
            name: "",
            title: "",
            description: "",
            route: "/" + Date.now().toString(16),
            components: [],
            styles: "",
            variables: [],
            isChanged: true
        };
        opts.pages?.push(page);

        customize(page);
        startEdit(page);
    }



    function startEdit(page: Page) {

        function exteractParams(route: string) {
            const params: string[] = [];
            const match = route.match(/\{(.*?)\}/gim);
            if (match && match.length > 0) {

                match.forEach((param: string) => {
                    let val = param.replace(/^\{/, '').replace(/\}$/, '');
                    if (val.length > 0) params.push(val);
                })
            }

            return params;
        }

        function renderVariables(element: any, variables: {
            name: string
            value: string
        }[]) {

            function getLengthFromStartToCaret(el: any) {
                var caretOffset = 0;
                var sel, range;
                if (window.getSelection) {
                    sel = window.getSelection();
                    if (sel.rangeCount) {
                        range = sel.getRangeAt(0).cloneRange();
                        range.collapse(true);
                        range.setStart(el, 0);
                        caretOffset = range.toString().length;
                    }
                } else if ((sel = (document as any).selection) && sel.type != "Control") {
                    range = sel.createRange();
                    var rangeLen = range.text.length;
                    range.moveStart("character", -el.value.length);
                    caretOffset = range.text.length - rangeLen;
                }
                return caretOffset;
            }

            function placeCaretAtPosition(el, position) {
                el.focus();
                if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
                    var range = document.createRange();
                    var sel = window.getSelection() as any;
                    range.setStart(el.childNodes[0], (position || 0));
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else if (typeof (document.body as any).createTextRange != "undefined") {
                    var textRange = (document.body as any).createTextRange();
                    textRange.moveToElementText(el);
                    textRange.moveStart('character', position);
                    textRange.collapse(true);
                    textRange.select();
                }
            }

            $(element).html("")
                .append(
                    variables.map((variable) => {
                        let isNameDuplicate = (page.variables?.filter(v => v.name == variable.name) || []).length > 1 ? true : false;

                        return $(`<tr></tr>`)
                            .append(
                                $(`<td contenteditable class="${isNameDuplicate ? 'text-danger' : ''}">${variable.name}</td>`)
                                    .on('keydown', function (e) {
                                        if (e.code === 'Enter') {
                                            e.preventDefault();
                                            e.stopImmediatePropagation();
                                        }
                                    })
                                    .on('input', function (e) {

                                        if (page.variables?.find(v => v.name == $(this).text())) {
                                            $(this).addClass("text-danger");
                                        } else {
                                            $(this).removeClass("text-danger");
                                        }

                                        let selectLen = getLengthFromStartToCaret(this);
                                        if (/^\d{1,}|[^a-zA-Z-0-9_]+/g.test($(this).text())) {
                                            selectLen -= 1;
                                        }
                                        $(this).text($(this).text().replace(/^\d{1,}|[^a-zA-Z-0-9_]+/g, ''));
                                        if ($(this).text().length > 0 && selectLen > 0) {
                                            placeCaretAtPosition(this, selectLen);
                                        }
                                        variable.name = $(this).text();

                                    })
                                    .on("blur", () => {
                                        if (variable.name.length <= 0 && page.variables) {
                                            page.variables.splice(page.variables.indexOf(variable), 1);
                                        }
                                        renderVariables(element, page.variables || []);
                                    }),
                                $(`<td contenteditable>${variable.value}</td>`)
                                    .on('input', function (e) {
                                        variable.value = $(this).text();
                                    }),
                            )
                    })
                )
        }


        var __params = {};
        let storageParam = window.localStorage.getItem('test-params');
        if (storageParam) {
            __params = JSON.parse(storageParam);
        }
        function testVariable() {

            if (!page.variables) {
                page.variables = [];
            }
            if ((page.variables || []).length <= 0) {
                __.toast('There are no variables to test', 5, 'text-warning');
                return;
            }

            const params = exteractParams(page.route);

            params.forEach(param => {
                if (!__params[param]) {
                    __params[param] = "";
                }
            });
            Object.keys(__params).forEach(param => {
                if (!params.includes(param)) {
                    delete __params[param];
                }
            })

            const content = $("<div>")
                .append(

                    $(`<label class='form-label mb-1 fw-bold'>Params :</label>`),
                    params.length > 0
                        ? $(`<table class="table table-bordered">`)
                            .css("font-size", "smaller")
                            .append(
                                $(`<colgroup>`)
                                    .append(
                                        $(`<col>`),
                                        $(`<col style="width: 75%;">`)
                                    ),
                                $(`<thead>`)
                                    .append(
                                        $(`<tr>`)
                                            .append(
                                                $(`<th scope="col">Name</th>`)
                                            )
                                            .append(
                                                $(`<th scope="col">Value</th>`)
                                            )
                                    ),
                                $(`<tbody id="test-param-item">`)
                            )
                        : "",

                    $(`<label class='form-label mb-1 fw-bold'>Variables :</label>`),
                    $(`<table class="table table-bordered">`)
                        .css("font-size", "smaller")
                        .append(
                            $(`<colgroup>`)
                                .append(
                                    $(`<col>`),
                                    $(`<col style="width: 75%;">`)
                                ),
                            $(`<thead>`)
                                .append(
                                    $(`<tr>`)
                                        .append(
                                            $(`<th scope="col">Name</th>`)
                                        )
                                        .append(
                                            $(`<th scope="col">Value</th>`)
                                        )
                                ),

                            $(`<tbody id="test-variable-item">`)
                        )

                )
                .append(
                    $(`<pre></pre>`)
                        .css({
                            fontSize: "0.65rem",
                            border: "1px solid rgb(0 0 0 / 18%)",
                            padding: "0.3rem",
                            borderRadius: "0.3rem",
                            backgroundColor: "rgba(0,0,0)",
                            color: "white",
                            overflow: "auto",
                            height: "100%",
                            minHeight: "150px",
                            maxHeight: "280px",
                            scrollbarWidth: "none",
                            whiteSpace: "pre"
                        })
                )


            const modal = $("#modal-test-variable").length <= 0 ? __.modal.create("Test Variable", content) : __.modal.from($("#modal-test-variable"));
            modal.el.prop('id', 'modal-test-variable');
            $(modal.el).find("#test-param-item")
                .html("")
                .append(
                    Object.keys(__params).map((index) => $(`<tr>`)
                        .append(
                            $(`<td></td>`).text(index)
                        )
                        .append(
                            $(`<td contenteditable="true"></td>`).text(__params[index])
                                .on('keydown', function (e) {
                                    if (e.code === 'Enter') {
                                        e.preventDefault();
                                        e.stopImmediatePropagation();
                                    }
                                })
                                .on('keyup', function (e) {
                                    if (e.keyCode === 13) {
                                        e.preventDefault();
                                        __params[index] = $(this).text();
                                        render();
                                    }
                                    __params[index] = $(this).text();
                                    window.localStorage.setItem('test-params', JSON.stringify(__params));
                                })
                        )
                    )
                );

            $(modal.el).find("#test-variable-item")
                .html("")
                .append(
                    page.variables.map(variable => $(`<tr>`)
                        .append(
                            $(`<td></td>`).text(variable.name)
                        )
                        .append(
                            $(`<td></td>`).text(variable.value)
                        )
                    )
                )

            modal.show();
            modal.action.positive = {
                text: 'Send',
                callback: send
            }



            function send() {

                $(modal.el).find('pre').html("");
                const data = {
                    variables: page.variables,
                    params: __params
                };

                Promise.resolve(editor.runCommand('pages:test-variable', data)).then(function (data) {
                    $(modal.el).find('pre').html("");
                    Object.keys(data).forEach(key => {
                        const value = (typeof data[key] == "string") ? data[key] : JSON.stringify(data[key], null, 2)
                            .replace(/(?<=\:)(\s+|)(null|undefined|true|false)+/g, "<span style='color: #ce55d9;'>$&</span>")
                            .replace(/\".*\"(\s+|)(?=\:)/g, "<span style='color: #88c9ff;'>$&</span>")
                            .replace(/(?<=\:)(\s+|)\".*\"/g, "<span style='color: #67d66c;'>$&</span>");

                        $(modal.el).find('pre').append(`<span style='color: #88c9ff;'>${key}</span>: ${value}\n`);
                    })
                })
            }
        }


        editor.select();
        var params = exteractParams(page.route);
        const el = $(".page-metadata");
        el.empty();
        el.css({
            position: "absolute",
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            zIndex: 2,
            padding: "0 1rem",
            width: "100%",
            height: "100%",
            background: "#FFF",
            display: "flex",
            flexDirection: "column",
        })

        el.append(
            $(`<div class="d-flex align-items-center mb-3 pt-2">`)
                .append(
                    $(`<button type="button" class="btn btn-sm text-primary border-primary rounded-0 border py-0 m-1">Go Back</button>`)
                        .on('click', stopEdit),
                    $(`<h5 class="ms-2 mb-0">Edit Page</h5>`),

                )
        )
            .append(
                $(`<div class="flex-grow-1 w-100 pb-5 scrollbar-none">`)
                    .css({
                        overflow: "auto"
                    })
                    .append(
                        $(`<form class='needs-validation mx-2'>`)
                            .append(
                                $(`<div class="row">`)
                                    .append(
                                        $(`<div class="col-12 col-lg-7">`)
                                            .append(
                                                $("<div class='form-group'>")
                                                    .append($("<label class='form-label w-100' for='title'>Title</label>")
                                                        .append(
                                                            $(`<input type='text' class='form-control' id='title' placeholder='Enter title' value='${page?.title}' maxlength="255" required>`)
                                                                .on('input', function () {
                                                                    page.title = $(this).val() as string
                                                                    $("#title-count").html(`${(page?.title || "").length}/255`)
                                                                    render();
                                                                }),
                                                            $(`<small id="title-count">${(page?.title || "").length}/255</small>`)
                                                        ))
                                            )
                                            .append(
                                                $("<div class='form-group'>")
                                                    .append($("<label class='form-label w-100' for='description'>Description</label>")
                                                        .append(
                                                            $(`<textarea type='text' class='form-control' id='description' placeholder='Enter description' rows='3' maxlength="255">${(page?.description || "")}</textarea>`)
                                                                .on('input', function () {
                                                                    page.description = $(this).val() as string
                                                                    $("#description-count").html(`${(page?.description || "").length}/255`)
                                                                    render();
                                                                }),
                                                            $(`<small id="description-count">${(page?.description || "").length}/255</small>`)
                                                        ))
                                            )
                                            .append(
                                                $("<div class='form-group'>")
                                                    .append($("<label class='form-label w-100' for='route'>Route</label>")
                                                        .append(
                                                            $(`<input type='text' class='form-control' id='route' placeholder='Enter route' value='${page?.route}' maxlength="255" ${((page.name || '').toLocaleLowerCase() == "index" ? "disabled" : "")} required>`)
                                                                .on('input', function () {
                                                                    page.route = $(this).val() as string
                                                                    $("#route-count").html(`${(page?.route || "").length}/255`)
                                                                    render();
                                                                    const params = exteractParams(page.route);
                                                                    $("#params").html("");
                                                                    if (params.length > 0) {
                                                                        $("#params").append(
                                                                            $(`<div class="fw-semibold">Route params</div>`),
                                                                            params.map((param) => {
                                                                                return $(`<span class="badge bg-primary text-white me-1">${param}</span>`)
                                                                            })
                                                                        )
                                                                    }
                                                                }),
                                                            $(`<small id="route-count">${(page?.route || "").length}/255</small>`)
                                                        ),
                                                        $(`<div id="params">`)
                                                            .append(
                                                                params.length > 0 ? $(`<div class="fw-semibold">Route params</div>`) : "",
                                                                params.length > 0 ? params.map((param) => {
                                                                    return $(`<span class="badge bg-primary text-white me-1">${param}</span>`)
                                                                }) : ""
                                                            )
                                                    )
                                            )
                                    )
                            )

                            .append(
                                $("<div class='form-group py-2'>")
                                    .append(
                                        $("<h4>Metadata</h4>"),

                                        $("<div class='form-group mb-3'>")
                                            .css({
                                                border: "1px solid #dee2e6",
                                                padding: "0.5rem",
                                            })
                                            .append(
                                                $("<label class='form-label w-100' for='variable'>Variable</label>"),
                                                $(`<table class='table table-bordered w-100'>`)
                                                    .append(
                                                        $(`<colgroup></colgroup>`)
                                                            .append(
                                                                $(`<col></col>`)
                                                                    .css({
                                                                        width: "20%"
                                                                    }),
                                                                $(`<col></col>`)
                                                                    .css({
                                                                        width: "80%"
                                                                    })
                                                            )
                                                    )
                                                    .append(
                                                        $(`<thead></thead>`)
                                                            .append(
                                                                $(`<tr></tr>`)
                                                                    .append(
                                                                        $(`<th>Name</th>`),
                                                                        $(`<th>Value</th>`)
                                                                    )
                                                            )
                                                    )
                                                    .append(
                                                        $(`<tbody id="variable"></tbody>`)
                                                    ),
                                                $(`<div>`)
                                                    .append(
                                                        $(`<button type="button" class="btn btn-sm btn-outline-primary px-4 py-1 rounded-0">Add</button>`)
                                                            .on('click', function () {
                                                                page.variables = page.variables || [];
                                                                let x = 0;
                                                                while (page.variables.find((v) => v.name == "var" + x)) x++;
                                                                page.variables.push({
                                                                    name: "var" + x,
                                                                    value: "",
                                                                });
                                                                renderVariables($("#variable"), page.variables)
                                                            }),
                                                        $(`<button type="button" class="btn btn-sm btn-outline-secondary px-4 py-1 rounded-0">Test</button>`)
                                                            .on('click', () => testVariable())
                                                    )
                                            ),

                                        $("<div class='form-group'>")
                                            .css({
                                                border: "1px solid #dee2e6",
                                                padding: "0.5rem",
                                            })
                                            .append(
                                                $("<label class='form-label w-100' for='meta-header'>Header</label>"),
                                                $(`<div id='meta-header'></div>`)
                                            )
                                    )
                            )
                    )
            )

        renderVariables($("#variable"), page.variables || []);

        new EditorView({
            doc: page.header || "",
            extensions: [
                basicSetup,
                html(),
                autocompletion(),
                EditorView.updateListener.of((update) => {
                    if (update.docChanged) {
                        const content = update.state.doc.toString();
                        page.header = content;
                    }
                })
            ],
            parent: el.find("#meta-header")[0]
        });




        return el;
    }




    function stopEdit() {
        $(".page-metadata").css({
            position: "relative",
            display: "none",
        }).empty();
    }


    var delay: any = null;
    editor.on("update", (e) => {
        if (delay) clearTimeout(delay);
        delay = setTimeout(() => {
            if (customizePage) {
                if (JSON.stringify(customizePage.components).replace(/(\n|\s)+/gim, "") !== JSON.stringify(editor.getComponents()).replace(/(\n|\s)+/gim, "")) {
                    customizePage.components = JSON.parse(JSON.stringify(editor.getComponents())) as any;
                }
                if ((customizePage.styles || '').replace(/(\n|\s)+/gim, "") !== (editor.getCss() || "").replace(/(\n|\s)+/gim, "")) {
                    customizePage.styles = editor.getCss() as any;
                }
            }
        }, 800);
    });

    editor.on("run", (e) => {

        const ignored = ["pages:test-variable", "core:component-delete", "pages:save", "pages:delete", "pages:customize"];
        if (!e || !ignored.includes(e)) {
            stopEdit();
        }
    });

}