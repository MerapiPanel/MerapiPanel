import $ from "jquery";
import "./style.css";
import "./app.scss";
import "/node_modules/flag-icons/css/flag-icons.min.css";
import "./icon.scss";

if (!window.$) {
    window.$ = $;
}


function xBind(callabel) {
    eval(callabel);
}

const Container = {
    "div[bg-image]": {
        init(el) {
            el.css("background-image", `url(${el.attr("bg-image")})`);
        }
    },
    ".x-dropdown": {
        init(el) {

            for (let dropdown of el) {
                const $dropdown = $(dropdown);
                const toggler = $dropdown.find(".x-toggler");

                function __(e) {
                    if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
                        $dropdown.removeClass("open");
                        $(document).off("click", __, __);
                        toggler.removeClass("active");
                        xBind.call(toggler, toggler.attr("x-off"));
                    }
                }
                toggler.on("click", () => {
                    $dropdown.toggleClass("open");
                    if ($dropdown.hasClass("open")) {
                        $(document).on("click", __, __);
                        toggler.addClass("active");
                        xBind.call(toggler, toggler.attr("x-on"));
                    } else {
                        $(document).off("click", __, __);
                        toggler.removeClass("active");
                        xBind.call(toggler, toggler.attr("x-off"));
                    }
                });
            }


        }
    },
    ".x-collapse": {
        init(el) {

            for (let collapse of el) {
                const $collapse = $(collapse);
                if ($collapse.attr("id")) {

                    const toggler = $(`[x-toggler="#${$collapse.attr("id")}"]`);

                    function __(e) {
                        if (!$collapse.is(e.target) && $collapse.has(e.target).length === 0) {
                            $collapse.removeClass("open");
                            $(document).off("click", __, __);
                            toggler.removeClass("active");
                            xBind.call(toggler, toggler.attr("x-off"));
                        }
                    }

                    if ($collapse.hasClass("open")) {
                        $(document).on("click", __, __);
                        xBind.call(toggler, toggler.attr("x-on"));
                    }


                    toggler.on("click", () => {

                        $collapse.toggleClass("open");

                        setTimeout(() => {
                            if ($collapse.hasClass("open")) {
                                $(document).on("click", __, __);
                                toggler.addClass("active");
                                if (toggler.attr("x-on")) {
                                    //eval(toggler.attr("x-on"));
                                    xBind.call(toggler, toggler.attr("x-on"));
                                }
                                if ($collapse.parent(".x-navbar")) {
                                    let navMenu = $collapse.parent(".x-navbar").find(".x-navbar-menu");
                                    if (navMenu.length > 0) {
                                        navMenu.removeClass("open");
                                    }
                                }
                            } else {
                                $(document).off("click", __, __);
                                toggler.removeClass("active");
                                if (toggler.attr("x-off")) {
                                    xBind.call(toggler, toggler.attr("x-off"));
                                    // eval(toggler.attr("x-off"));
                                }
                            }
                        }, 200);
                    });
                }
            }

            function nestedInNavbar() {

            }
        }
    },
    ".x-navbar": {
        init(el) {

            const toggler = el.find(".x-navbar-toggler");
            const menu = el.find(".x-navbar-menu");

            function clickOutside(e) {
                if (!menu.is(e.target) && menu.has(e.target).length === 0) {
                    menu.removeClass("open");
                    $(document).off("click", clickOutside);
                }
            }



            if (menu.length > 0 && toggler.length > 0) {
                toggler.on("click", () => {
                    menu.toggleClass("open");
                    setTimeout(() => {
                        if (menu.hasClass("open")) {
                            $(document).on("click", clickOutside);
                        } else {
                            $(document).off("click", clickOutside);
                        }
                    }, 200)
                });
            }

        }
    }
}



$(() => live());

const live = () => {
    Object.keys(Container).forEach((selector) => {
        const element = $(selector);
        if (!element.length || element.data("init")) {
            return;
        }

        Container[selector].init(element);
        element.data("init", true);
    });

    setTimeout(() => {
        window.requestAnimationFrame(live);
    }, 400);
}
