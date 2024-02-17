import { Wdget } from "./tools/Widget.ts"

$(function () {


    //   console.log(widgetPayload)



    if (!widgetPayload) return;

    function createUrl(path) {
        if (typeof window !== "undefined") {
            let url = new URL(path, window.location.origin);
            return url.toString();
        }
        throw new Error("No window context");
    }


    const holder = widgetPayload.holder;
    const endpoints = widgetPayload.endpoints;

    const widget = new Wdget(holder);

    var EditWidgetHandler = $(`<button class="btn btn-sm btn-outline-secondary !px-3 !py-1 ms-3"><i class="fa-solid fa-pen-to-square"></i></button>`);
    $("#panel-header").append(EditWidgetHandler);

    widget.setToggler(EditWidgetHandler);

    console.log(widget)

    widget.wgetEditing.onToggle(function (isOpen) {
        if (isOpen) {
            $(document.body).find("#sidenav").css("display", "none");
        } else {
            $(document.body).find("#sidenav").css("display", "unset");
        }
    });


    widget.holder.on("widget:edit:close", function () {
        merapi.http.post(createUrl(endpoints.save), { data: JSON.stringify(widget.getData()) }).then((response, text, xhr) => {
            if (xhr.status === 200) {
                merapi.toast(response.message, 5, 'text-success');
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
        }
    })
});