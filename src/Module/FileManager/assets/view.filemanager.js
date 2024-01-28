import Merapi from '../../../base/assets/merapi';
const { parseHTML } = require("jquery");

const FileManager = {}
FileManager.container = {
    "root": null,
    "data": []
};

FileManager.setContainer = (rawdata) => {
    FileManager.container = JSON.parse(parseHTML(rawdata)[0].textContent)
}

FileManager.ItemFocusHandle = (element) => {

    let childs = $(element).parent().find($(element).prop("tagName").toLowerCase());
    childs.each((x) => { $(childs[x]).removeClass('active'); })

    $(element).addClass("active")
}



FileManager.infoFile = (x) => {

    const data = FileManager.container.data[x]

    hide($(".offcanvas"))

    const content = $(`<div class="offcanvas shadow-md transition-all duration-100 w-full -mr-[400px] [&.show]:mr-0 opacity-0 [&.show]:opacity-100 max-w-[400px] z-[25] fixed bottom-0 right-0 p-5 bg-white/90 backdrop:blur-[2px] rounded-l">
    <div class="offcanvas-header pb-5 relative">
      <h5 class="offcanvas-title font-bold py-1" id="offcanvasLabel">File Detail</h5>
      <button data-mp-act="close" type="button" class="w-[35px] h-[35px] absolute top-0 right-0 hover:text-red-400 hover:scale-105"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="offcanvas-body w-full">
      <table class='w-full text-sm'> 
        <colgroup>
            <col style="width: 100px"/>
            <col/>
        </colgroup>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>File name</td>
            <td class='break-all pb-2'>${data.name}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>File path</td>
            <td class='break-all pb-2'>${data.path}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Size</td>
            <td class='break-all pb-2'>${Math.floor(data.size / 1024)}Kb</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Last modify</td>
            <td class='break-all pb-2'>${data.time}</td>
        </tr>
        <tr class='align-baseline'>
            <td class='text-left whitespace-nowrap pr-5 pb-2'>Mime type</td>
            <td class='break-all pb-2'>${data.type}</td>
        </tr>
      </table>
    </div>
  </div>`)



    setTimeout(() => {
        $(document.body).append(content)
        setTimeout(() => content.addClass("show"), 50)

        $(window).on('click', function () {
            hide(content)
        });

        $(content).on('click', function (event) {
            event.stopPropagation();
        });
        $("[data-mp-act=\"close\"]").on('click', () => hide(content))
    }, 50);

    function hide(element) {

        $(element).removeClass("show")
        setTimeout(() => $(element).remove(), 50);
    }
}



/**
 * Create a new folder using the provided options.
 *
 * @param {object} opts - The options for creating the folder, including the name and endpoint.
 * @return {Promise} A Promise that resolves with the response from the server.
 */
FileManager.createFolder = (opts) => {

    let fm = new FormData();
    fm.append("name", opts.name);
    fm.append("parent", opts.parent);

    Merapi.post(opts.endpoint, fm).then((res, status, xhr) => {
        if (xhr.status === 200) {
            Merapi.toast(res.message, 5, 'text-success');
            window.location.reload();
        } else {
            Merapi.toast(res.message, 5, 'text-danger');
        }
    }).catch((err) => {

        Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
    })
}




/**
 * Deletes a folder using the provided arguments.
 *
 * @param {Object} args - An object containing file, type, and endpoint information
 * @return {void}
 */
FileManager.deleteFolder = (args) => {
    const opts = Object.assign({ file: null, type: 'directory', endpoint: null }, args);
    Merapi.confirmDelete("Confirm deletion", `<p>Are you sure you want to delete this ${opts.type}?<br/><i>${opts.file}</i></p>This action cannot be undone!!!.`)
        .then((result) => {

            if (result) {
                const fm = new FormData();
                fm.append("file", opts.file);

                Merapi.post(args.endpoint, fm).then((res, status, xhr) => {
                    if (xhr.status === 200) {
                        Merapi.toast(res.message, 5, 'text-success');
                        window.location.reload();
                    } else {
                        Merapi.toast(res.message, 5, 'text-danger');
                    }
                }).catch((err) => {
                    Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                });
            }
        })
}



FileManager.renameFile = (args) => {
    const opts = Object.assign({ file: null, type: 'directory', name: null, endpoint: null }, args);

    const element = $(`<div><input class='text-input' value="${opts.name}"/><small class='text-yellow-400 bg-yellow-500/20 px-2 py-1 mt-1'><b>Noted :</b> Changing file name may break other content that used this file!.</small></div>`);
    const modal = Merapi.createModal(`Rename ${opts.type}`, element, {
        positive: {
            text: 'Rename',
            class: 'btn btn-primary',
            callback: () => {
                const fm = new FormData();
                fm.append("file", opts.file);
                fm.append("old_name", opts.name);
                fm.append("new_name", element.find("input").val())
                Merapi.post(opts.endpoint, fm).then((res, status, xhr) => {
                    if (xhr.status === 200) {
                        Merapi.toast(res.message, 5, 'text-success');
                        window.location.reload();
                    } else {
                        Merapi.toast(res.message, 5, 'text-danger');
                    }
                }).catch(err => {
                    Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                })
            }
        },
        negative: {
            text: 'Close',
            class: 'btn btn-secondary',
            callback: null
        }
    });

    modal.show();
}


// assign to global
window.FileManager = FileManager;