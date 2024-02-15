

export default class WidgetBox {
    name = "Widget Box";
    type = "box";
    category = "box";
    content = "<div class='widget-box'></div>";
    component = null;

    constructor(args = {}) {

        args = {
            ...{
                name: "Widget",
                type: "box",
                content: null,
                category: "box"
            }, ...args
        };

        this.name = args.name;
        this.type = args.type;
        this.content = args.content;
        this.category = args.category;
        this.component = $(`<div class='widget-component-item'><i class="fa-solid fa-expand"></i><span>${this.name}</span></div>`);

        $(this.component).on("mousedown touchstart", (e) => {
            this.__dragComponent(e);
        });
    }

    getName() {
        return this.name
    }

    getType() {
        return this.type
    }

    getCategory() {
        return this.category
    }

    getContent() {
        return this.content
    }


    __dragComponent(event) {

        const isCancel = false;
        var currentBox = null, currentContainer = null;
        const drag = $(event.currentTarget).clone();

        $(document.body).append(drag);

        var pos_x = event.pageX || event.originalEvent.touches[0].pageX;
        var pos_y = event.pageY || event.originalEvent.touches[0].pageY;

        drag.css({
            'position': 'absolute',
            'z-index': '99999',
            'top': (pos_y - (drag.height() / 2)) + 'px',
            'left': (pos_x - (drag.width() / 2)) + 'px',
        });

        $(document.body).css('overflow', 'hidden');

        $(document).on("mousemove touchmove", (e) => {

            var pos_x = e.pageX || e.originalEvent.touches[0].pageX;
            var pos_y = e.pageY || e.originalEvent.touches[0].pageY;

            drag.css({
                'top': (pos_y - (drag.height() / 2)) + 'px',
                'left': (pos_x - (drag.width() / 2)) + 'px'
            });

            const toolbar = $(".widget-toolbar");

            if (this.type == "box") {



                $(".widget-container").each(function () {
                    var container = $(this);
                    var containerPos = container.offset();
                    var containerRight = containerPos.left + container.width();
                    var containerBottom = containerPos.top + container.height();


                    if (!(pos_x < toolbar.offset().left || pos_x > toolbar.offset().left + toolbar.width() || pos_y < toolbar.offset().top || pos_y > toolbar.offset().top + toolbar.height())) {
                        //$(".widget-container").removeClass("highlight");
                        //currentContainer = null;
                        //$("div.mook-box").remove();
                        return;
                    }

                    if (pos_x > containerPos.left && pos_x < containerRight && pos_y > containerPos.top && pos_y < containerBottom) {
                        if (currentContainer != container[0]) {
                            //$(".widget-container").removeClass("highlight");
                            //container.addClass("highlight");
                            currentContainer = container[0];
                            $(currentContainer).trigger("drag:above", { e: e });
                        }
                    }

                    // $(".widget-container .widget-box").each(function () {
                    //     var box = $(this);
                    //     var boxPos = box.offset();
                    //     var boxRight = boxPos.left + box.width();
                    //     var boxBottom = boxPos.top + box.height();

                    //     if (pos_x > boxPos.left && pos_x < boxRight && pos_y > boxPos.top && pos_y < boxBottom) {
                    //         if (currentBox != box[0]) {

                    //             $("div.mook-box").remove();
                    //             const mookBox = $(`<div class='widget-box mook-box'></div>`);

                    //             if (pos_y > (boxPos.top + (box.height() / 2))) {
                    //                 // insert after
                    //                 mookBox.insertAfter(box);
                    //             } else if (pos_y < (boxPos.top + (box.height() / 2))) {
                    //                 // insert before
                    //                 mookBox.insertBefore(box);
                    //             }
                    //         }
                    //     }
                    // });
                });
            } else {

                $(".widget-box").each(function () {
                    var box = $(this);
                    var boxPos = box.offset();
                    var boxRight = boxPos.left + box.width();
                    var boxBottom = boxPos.top + box.height();


                    if (!(pos_x < toolbar.offset().left || pos_x > toolbar.offset().left + toolbar.width() || pos_y < toolbar.offset().top || pos_y > toolbar.offset().top + toolbar.height())) {
                        $(".widget-box").removeClass("highlight");
                        currentBox = null;
                        return;
                    }

                    if (pos_x > boxPos.left && pos_x < boxRight && pos_y > boxPos.top && pos_y < boxBottom) {
                        if (currentBox != box[0]) {
                            $(".widget-box").removeClass("highlight");
                            box.addClass("highlight");
                            currentBox = box[0];
                        }
                    }
                });
            }

        });

        $(document).on("mouseup touchend", function () {
            $(document.body).removeAttr('style');
            $(document).off("mousemove");
            $(document).off("touchmove");
            $(".widget-container").removeClass("highlight");
            $(".widget-box").removeClass("highlight");
            drag.remove();
        });
    }


    render() {
        return this.component;
    }
}