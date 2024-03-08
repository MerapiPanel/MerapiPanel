import "./scss/app.scss";

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