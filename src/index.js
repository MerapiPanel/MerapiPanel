require('./app.css')
require('./base/assets/fontawesome/css/all.min.css');
const $ = require('jquery');
import merapi from './merapi';

window.$ = $;
window.merapi = merapi;


let _mod = decodeURIComponent(merapi.getCookie('_module'));
merapi.setCookie('_module', '', -1);

if (_mod) {
    _mod = JSON.parse(_mod);
    for (let i in _mod) {
        require('./Module/' + _mod[i] + "/Assets/app.js");
    }
}


const acts = {

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