import grapesjs from "grapesjs";
import $ from "jquery";


$(document).on('DOMContentLoaded', function () {

    var editor = grapesjs.init({
        container : '#gjs',
        components: '<div class="txt-red">Hello world!</div>',
        style: '.txt-red{color: red}',
    });

})





