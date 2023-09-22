(() => {
    if (typeof window.$ == 'undefined' || window.jQuery == undefined) {
        console.error('Merapi Panel is required jQuery');
    }
})();

const proggress = $(`<div class='http-progress'><div class='download running-strip'></div><div class='upload running-strip'></div></div>`);
var isOnAjax = false;
$(document).on("ajaxSend", function (e) {
    $(document.body).append(proggress);
    isOnAjax = true;
}).on("ajaxComplete", function () {
    $('.http-progress').remove();
    isOnAjax = false;
});


function changeStrStyle(string, key, value) {

    let reg = new RegExp(`${key}\:.*?(;|$)`, 'g');
    if (reg.test(string)) {
        string = string.replace(reg, `${key}: ${value};`);
    } else {
        string = (string[string.length - 1] === ';' ? string.substring(0, string.length - 1) : string) + `; ${key}: ${value};`;
    }
    return string;
}

function ajaxRequest(url, data = null, callback) {

    if (isOnAjax) {
        alert('Please wait...');
        return;
    }

    proggress.css({
        position: 'fixed',
        width: '100%',
        height: '5px',
        'background-color': '#00000000',
        'box-shadow': '0 1px 2px #00000045',
        top: 0,
        'z-index': 99,
    })
    proggress.find('.download').css({
        transision: '1s',
        position: 'absolute',
        top: 0,
        width: '0%',
        height: '100%',
        'background-color': '#0091ff',
        'z-index': 2
    })
    proggress.find('.upload').css({
        transision: '1s',
        position: 'absolute',
        top: 0,
        width: '5%',
        height: '100%',
        'background-color': '#eaeaea',
        'z-index': 1
    })

    $.ajax({
        xhr: function () {

            let xhr = new window.XMLHttpRequest();
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    let complete = (evt.loaded / evt.total * 100 | 0);
                    $('.http-progress').find('.download').css('width', `${complete}%`);
                }
            });

            xhr.upload.addEventListener("progress", (evt) => {
                if (evt.lengthComputable) {
                    let complete = Math.ceil((evt.loaded / evt.total) * 100);
                    $('.http-progress').find('.upload').css('width', `${complete}%`);
                }
            });
            return xhr;
        },
        url: url,
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: callback
    })
}


const Merapi = {};
Merapi.get = (url, callback) => ajaxRequest(url, null, callback);
Merapi.post = (url, data, callback) => ajaxRequest(url, data, callback);