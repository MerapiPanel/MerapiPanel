(() => {
    if (typeof window.$ == 'undefined' || window.jQuery == undefined) {
        console.error('Merapi Panel is required jQuery');
    }
})();

const proggressbars = $(`<div class='http-progress'><div class='download running-strip'></div><div class='upload running-strip'></div></div>`);
var isOnAjax = false;
$(document).on("ajaxSend", function (e) {
    $(document.body).append(proggressbars);
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
        Toast.create('Please wait...', 'hsl(51, 50%, 45%)', 10);
        return;
    }

    proggressbars.css({
        position: 'fixed',
        width: '100%',
        height: '5px',
        'background-color': '#00000000',
        'box-shadow': '0 1px 2px #00000045',
        top: 0,
        'z-index': 99,
    })
    proggressbars.find('.download').css({
        transision: '1s',
        position: 'absolute',
        top: 0,
        width: '0%',
        height: '100%',
        'background-color': '#0091ff',
        'z-index': 2
    })
    proggressbars.find('.upload').css({
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

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const Toast = {};
Toast.enable = true;
Toast.create = function (text = "", textColor = "#0000ff45", seconds = 3) {

    if (!Toast.enable) return;
    Toast.enable = false;
    setTimeout(() => Toast.enable = true, 400)

    let max = 5;
    let posY = 100;
    let toast = $(`<toast style='background: white;
                                color: ${textColor};
                                 width: 100%;
                                 transition: 0.25s;
                                 max-width: 350px;
                                 box-shadow: 0 0 4px #00000044;
                                 padding: 15px 35px 15px 15px;
                                 position: fixed;
                                 top: 100vh;
                                 right: -3000px;
                                 border-radius: 0.3rem;
                                 z-index: 900;'>`)
    let icon = $(`<icon style='display: inline-flex;
                               border: 1px solid;
                               border-radius: 5rem;
                               width: 1.75rem;
                               height: 1.75rem;
                               justify-content: center;
                               align-items: center;
                               transform: rotate(-15deg);
                               margin: 0 10px 0 0;'>
                    <i class="fa-solid fa-exclamation"></i>
                </icon>`)
    let message = $(`<message>${text}</message>`)
    let close = $(`<btn type='button' 
                        style="position: absolute;
                               top: 0;
                               right: 0;
                               color: #ff000078;
                               padding: 0.5rem;">
                        <i class="fa-solid fa-x"></i>
                    </btn>`)

    let progress = $(`<div class="progress" 
                           style='position: absolute;
                           overflow:hidden;
                                    width: 100%;
                                    height: 4px;
                                    left: 0;
                                    bottom: -1px;'>
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuemax="100"></div>
                     </div>`)

    toast.append(icon, message, close, progress)

    let lastToasts = $("toast")
    if (lastToasts.length > max) {
        return;
    }



    for (let i = 0; i < lastToasts.length; i++) {
        let lastToast = lastToasts[i];
        posY += (lastToast.offsetHeight) + 10;
    }
    toast.css("top", posY + "px")


    $(document.body).append(toast)

    let progressbar = progress.find(".progress-bar");
    progressbar.css("transition", `${seconds}s`)
    setTimeout(() => {
        toast.css("right", 25 + "px")
        progressbar.css("width", "0%")
    }, 50)

    let delay = 1000 * seconds;
    let timeOut = setTimeout(() => {
        toast.remove()
        Toast.control()
    }, delay)

    close.on("click", () => {
        toast.remove();
        clearTimeout(timeOut)
    })

}
Toast.control = function () {

    let lastToasts = $("toast")
    let posY = 100;
    for (let i = 0; i < lastToasts.length; i++) {
        let lastToast = $(lastToasts[i]);
        let Y = Number(lastToast.css("top").replace(/[^0-9]+/gi, ''))
        lastToast.css("top", posY + "px");
        posY = Y
    }
}


const Merapi = {};
Merapi.get = (url, callback) => ajaxRequest(url, null, callback);
Merapi.post = (url, data, callback) => ajaxRequest(url, data, callback);