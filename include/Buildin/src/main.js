import "../scss/app.scss";
import "../vendor/fontawesome/css/all.min.css";
import $ from "jquery";
import { toast } from "@il4mb/merapipanel";

window.$ = $;

$.ajax({
    error: (e) => {
        console.log(e);

        if (e.responseJSON && e.responseJSON.message) {
            toast(e.responseJSON.message, 10, 'text-' + (e.code >= 401 ? 'danger' : 'warning'));
        } else {
            toast(e.statusText || e.responseText, 10, 'text-' + (e.code >= 401 ? 'danger' : 'warning'));
        }
    }
})


// console.log(window)

$(() => {
    $("img").each(function () {

        let $this = $(this);
        if ($this[0].naturalWidth == 0 && $this[0].naturalHeight == 0) {
            $this.attr("error", true);
            let image = new Image();
            image.onload = () => {
                $this.removeAttr("error");
            }
            image.src = $this.attr("src");
        } else {
            $this.css("opacity", 1);
        }
    });
})