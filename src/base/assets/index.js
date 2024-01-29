require('./app.css')
require('./fontawesome/css/all.min.css');
const $ = require('jquery');
import Merapi from './merapi';

window.$ = $;
window.MERAPI = Merapi;
const acts = {} 

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

        console.log(target);
    });

    $('.dropdown').each(function () {
        const $this = $(this);
        $this.find('[data-act-trigger=dropdown]').on('click', function () {
            $('.dropdown').not($this).removeClass('open');
            $this.toggleClass('open');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest($this).length) {
                $this.removeClass('open');
            }
        })
    });
})