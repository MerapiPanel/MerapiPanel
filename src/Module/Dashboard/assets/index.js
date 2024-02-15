import { Wdget, Type } from "./tools/Widget.ts"

$(function () {



    const boxs = [
        { // box
            title: "Box 1",
            blocks: [
               
            ]
        },
        {
            title: "Box 2",
            blocks: [
                
            ]
        }
    ];

    const widget = new Wdget($('div.widget-container'), {
        data: boxs
    });

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

    
    widget.entityManager.addEntity("Content", {
        name: "Content",
        content: "<p>Content of the widget</p>",
        attributes: {
            "class": "widget-content"
        }
    })
    widget.entityManager.addEntity("Content2", {
        name: "Content",
        content: "<p>Content of the widget</p>",
        attributes: {
            "class": "widget-content"
        }
    })


    // EditWidget.on("click", function () {
    //     toggleEditWidget();
    // })




    // function toggleEditWidget() {


    //     if ($(document.body).hasClass("widget-editing")) {

    //         $(document.body).find("#sidenav").css("display", "unset");
    //         $(document.body).removeClass("widget-editing");
    //         $(document.body).find("div.widget-toolbar").removeClass("open");
    //         setTimeout(() => {
    //             $(document.body).find("div.widget-toolbar").remove();
    //         })

    //     } else {


    //         $(document.body).find("#sidenav").css("display", "none");


    //         $(document.body).append(toolbar);
    //         $(document.body).toggleClass("widget-editing");


    //         initComponents();

    //         setTimeout(() => {
    //             toolbar.addClass("open");


    //             widgetContainer.find(">div").each(function () {
    //                 let box = $(this);
    //                 box.addClass("widget-box");
    //                 box.find(">div").each(function () {
    //                     let item = $(this);
    //                     item.addClass("widget-item");
    //                     item.on("click", function () {
    //                         handleResize(this);
    //                     })
    //                 })
    //             })
    //         })
    //     }
    // }


    // function initComponents() {

    //     toolbar.html('');
    //     widgetComponents.forEach((component) => {
    //         let category = component.getCategory().toLowerCase();

    //         //if (category == "box") {

    //         //console.log(component.render());
    //         toolbar.append(component.render());
    //         //}
    //     })
    // }


    // function handleResize(target) {
    //     const $target = $(target);
    //     const resize = $('<div class="resize"></div>');
    //     const horizontalHandler = $('<button title="Resize horizontally" class="resize-handle resize-horizontal"><i class="fa-solid fa-left-right"></i></button>');
    //     const verticalHandler = $('<button title="Resize vertically" class="resize-handle resize-vertical"><i class="fa-solid fa-up-down"></i></button>');
    //     const cancelHandler = $('<button title="Remove item" class="resize-handle resize-remove"><i class="fa-solid fa-xmark"></i></button>');

    //     $('.resize').each(function () {
    //         removeFocus(this);
    //     });

    //     resize.insertBefore($target).append($target, horizontalHandler, verticalHandler, cancelHandler);

    //     let isResizingHorizontal = false, isResizingVertical = false;
    //     let startX, startY, startWidth, startHeight;

    //     horizontalHandler.add(verticalHandler).on('mousedown touchstart', function (e) {
    //         isResizingHorizontal = $(this).is('.resize-horizontal');
    //         isResizingVertical = $(this).is('.resize-vertical');
    //         startX = e.clientX || e.originalEvent.touches[0].clientX;
    //         startY = e.clientY || e.originalEvent.touches[0].clientY;
    //         startWidth = $target.width();
    //         startHeight = $target.height();
    //         e.preventDefault(); // Prevents text selection and touch actions.
    //     });

    //     let timeout = null;
    //     $(document).on('mousemove touchmove', function (e) {
    //         if (!isResizingHorizontal && !isResizingVertical) return;
    //         let moveX = e.clientX || e.originalEvent.touches[0].clientX;
    //         let moveY = e.clientY || e.originalEvent.touches[0].clientY;

    //         let newWidth = Math.max(startWidth + (moveX - startX), 200); // Minimum size of 50px
    //         let newHeight = Math.max(startHeight + (moveY - startY), 100); // Minimum size of 50px
    //         let roundedWidth = Math.round(newWidth / 50) * 50; // Corrected for 50px steps
    //         let roundedHeight = Math.round(newHeight / 50) * 50; // Corrected for 50px steps

    //         if (isResizingHorizontal) $target.width(roundedWidth);
    //         if (isResizingVertical) $target.height(roundedHeight);


    //     }).on('mouseup touchend', function () {
    //         isResizingHorizontal = false;
    //         isResizingVertical = false;
    //     });

    //     cancelHandler.on('click', function () {
    //         resize.remove();
    //     });

    //     $(document).on("click", function (e) {
    //         if (!resize.is(e.target) && resize.has(e.target).length === 0) {
    //             removeFocus(resize);
    //         }
    //     });

    //     function removeFocus(target) {
    //         let $target = $(target).children().first().detach();
    //         $(target).before($target).remove();
    //         $target.off("click").on("click", function () {
    //             handleResize(this);
    //         });
    //     }
    // }

});



