require('./app.css')
require('./fontawesome/css/all.min.css');
const $ = require('jquery');

if (!window.merapi) {
    window.merapi = require("./merapi");
    // console.log(window)
}



$(document).on('DOMContentLoaded', function () {

    $('[onload]').each(function () {
        const $this = $(this);
        try {
            eval($this.attr('onload'));
        } catch (e) {
            console.error(e);
        }
    });

    $('[data-act-target]').each(function () {

        let target = $this.attr('data-act-target');
    });

    liveReload();

   // console.clear()
})



function liveReload() {
    $('.dropdown').each(function () {
        const $this = $(this);

        // Check if the element already has the event listener attached
        if ($this.data('listener-attached')) {
            return; // Exit the loop for this element
        }

        // Attach the event listener
        $this.find('[data-act-trigger=dropdown]').on('click', function () {
            $('.dropdown').not($this).removeClass('open');
            $this.toggleClass('open');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest($this).length) {
                $this.removeClass('open');
            }
        });

        // Mark the element as having the event listener attached
        $this.data('listener-attached', true);
    });

    setTimeout(() => {
        window.requestAnimationFrame(liveReload);
    }, 800);
}
