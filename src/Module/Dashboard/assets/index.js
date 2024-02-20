import { Wdget } from "./tools/Widget.ts"

$(function () {

    if (!widgetPayload) return;


    const holder = widgetPayload.holder;
    const endpoints = widgetPayload.endpoints;

    const widget = new Wdget(holder);

    var EditWidgetHandler = $(`<button class="btn btn-sm btn-outline-secondary !px-3 !py-1 ms-3"><i class="fa-solid fa-pen-to-square"></i></button>`);
    $("#panel-header").append(EditWidgetHandler);

    widget.setToggler(EditWidgetHandler);

    widget.wgetEditing.onToggle(function (isOpen) {
        if (isOpen) {
            $(document.body).find("#sidenav").css("display", "none");
        } else {
            $(document.body).find("#sidenav").css("display", "unset");
        }
    });

    widget.holder.on("widget:ready", () => {
        loadContentWidget(widget);
    });


    widget.holder.on("widget:edit:close", function () {
        merapi.http.post(createUrl(endpoints.save), { data: JSON.stringify(widget.getData()) }).then((response, text, xhr) => {
            if (xhr.status === 200) {
                merapi.toast(response.message, 5, 'text-success');
                loadContentWidget(widget);
            }
        })
    })

    merapi.http.get(createUrl(endpoints.load)).then((response, text, xhr) => {
        if (xhr.status === 200 && response.data) {
            widget.setData(response.data)
        }
    })
    merapi.http.get(createUrl(endpoints.fetch)).then((response, text, xhr) => {
        if (xhr.status === 200 && response.data) {
            for (let i in response.data) {
                let model = response.data[i];
                widget.entityManager.addEntity(model.name, model)
            }

            console.log(widget.entityManager);
        }

        console.log(widget)
    })



    function GetDoc(x) {
        return x.contentDocument || x.contentWindow.document;
    }


    function createUrl(path) {
        if (typeof window !== "undefined") {
            let url = new URL(path, window.location.origin);
            return url.toString();
        }
        throw new Error("No window context");
    }


    function loadContentWidget(widget) {
        let blocks = [];
        widget.containers.forEach(element => {
            blocks = blocks.concat(element.blocks)
        });

        blocks.forEach(block => {

            $(block.el).html(`<div class='w-full h-full flex justify-center items-center text-gray-300'>
                <i class='fa fa-spinner fa-spin fa-lg'></i>
                <span class='ml-2'>Loading...</span>
            </div>`);

            setTimeout(() => {

                merapi.http.get(createUrl(endpoints.load + "/" + encodeURI(block.name))).then((response, text, xhr) => {
                    if (xhr.status === 200) {

                        if (typeof response.data === "object") {

                            var data = response.data;

                            if (data.content || (data.css && data.js)) {

                                block.el.html(`<iframe width="${block.attribute.width - 20}" height="${block.attribute.height - 10}" class='w-full h-full' frameborder='0' scrolling='no'></iframe>`);
                                let iframe = block.el.find("iframe")[0];
                                let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                                let stylesheets = [];
                                let scrips = [];

                                data.css.forEach(css => {
                                    stylesheets.push("<link rel='stylesheet' href='" + css + "'>");
                                })
                                data.js.forEach(js => {
                                    scrips.push("<script src='" + js + "'></script>");
                                })

                                iframeDoc.open();
                                iframeDoc.write(`<html style='width: ${block.attribute.width - 20}px; height: ${block.attribute.height - 10}px;'>\n
                                <head>\n
                                <style>html,body { margin: 0; padding: 0; }\n* { box-sizing: border-box; }\nbody { transition: 1s; animation: loading 1s infinite alternate; } body * { transition: 1s; opacity: 0; } body.loaded { display: block; animation: none; } body.loaded * { opacity: 1; } @keyframes loading { from { background-color: #f2f2f2; } to { background-color: transparent; } }</style>\n
                                ${stylesheets.join("\n")}\n
                                </head>\n
                                <body style='width: ${block.attribute.width - 20}px; height: ${block.attribute.height - 10}px;'>\n
                                ${data.content}\n
                                ${scrips.join("\n")}\n
                                </body>\n
                                </html>`);
                                iframeDoc.close();
                            }

                        } else {

                            $(block.el).html(response.data || "");
                        }
                    }
                }).catch(err => {
                    merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                    $(block.el).html(`<div class='w-full h-full flex justify-center items-center text-yellow-400'>
                        <i class=\"fa-solid fa-triangle-exclamation fa-lg\"></i>
                        <span class='ml-2'>Error while load widget</span>
                    </div>`);
                })
            }, 400);
        })
    }

});


