import Merapi from '../../../base/assets/merapi';
const { parseHTML } = require("jquery");
import Resumable from 'resumablejs';

function isNumeric(str) {
    if (typeof str != "string") return false // we only process strings!  
    return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

function formatFileSize(bytes) {
    if (bytes < 1024) {
        return bytes + ' bytes';
    } else if (bytes < 1024 * 1024) {
        return (bytes / 1024).toFixed(2) + ' KB';
    } else if (bytes < 1024 * 1024 * 1024) {
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    } else {
        return (bytes / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
    }
}

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

    const item = isNumeric(x) ? FileManager.container.data[x] : x;
    const data = Object.assign({ name: "", path: "", size: 0, time: "", type: "unknown" }, item);

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
            <td class='break-all pb-2'>${formatFileSize(data.size)}</td>
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
FileManager.createFolder = (args) => {

    const opts = Object.assign({ parent: null, endpoint: null }, args)

    return new Promise((resolve, reject) => {

        const body = $(`<div><small>Parent folder: <b>${opts.parent == '' ? '/' : opts.parent}</b></small><input class='text-input' placeholder="Enter folder name"/></div>`)
        const modal = MERAPI.createModal('Create New Folder', body);
        modal.setAction('+', {
            text: "Create",
            class: "btn btn-primary",
            callback: function () {
                window.FileManager.createFolder({
                    endpoint: opts.endpoint,
                    name: body.find('input').val(),
                    parent: opts.parent
                });
                let fm = new FormData();
                fm.append("name", body.find('input').val());
                fm.append("parent", opts.parent);

                Merapi.post(opts.endpoint, fm).then((res, status, xhr) => {
                    if (xhr.status === 200) {
                        Merapi.toast(res.message, 5, 'text-success');
                        resolve(res)
                    } else {
                        Merapi.toast(res.message, 5, 'text-danger');
                        reject(res)
                    }
                }).catch((err) => {
                    Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                    reject(err)
                })
            }
        })
        modal.show();
    })
}




/**
 * Deletes a folder using the provided arguments.
 *
 * @param {Object} args - An object containing file, type, and endpoint information
 * @return {void}
 */
FileManager.deleteFile = (args) => {

    const opts = Object.assign({ file: null, type: 'directory', endpoint: null }, args);

    return new Promise((resolve, reject) => {

        Merapi.confirmDelete("Confirm deletion", `<p>Are you sure you want to delete this ${opts.type}?<br/><i>${opts.file}</i></p>This action cannot be undone!!!.`)
            .then((result) => {
                if (result) {
                    const fm = new FormData();
                    fm.append("file", opts.file);

                    Merapi.post(args.endpoint, fm).then((res, status, xhr) => {
                        if (xhr.status === 200) {
                            Merapi.toast(res.message, 5, 'text-success');
                            resolve(res);
                        } else {
                            Merapi.toast(res.message, 5, 'text-danger');
                            reject(res);
                        }
                    }).catch((err) => {
                        Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                        reject(err);
                    });
                }
            })
    })
}



FileManager.renameFile = (args) => {

    const opts = Object.assign({ file: null, type: 'directory', name: null, endpoint: null }, args);

    return new Promise((resolve, reject) => {

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
                            resolve(res)
                        } else {
                            Merapi.toast(res.message, 5, 'text-danger');
                            reject(res)
                        }
                    }).catch(err => {
                        Merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
                        reject(err)
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
    })
}




FileManager.uploadFile = (args) => {

    const opts = Object.assign({ endpoint: null, parent: null }, args);

    return new Promise((resolve, reject) => {

        const body = $(`<div class='px-4 md:px-8'>
            <button class="sticky -top-3 z-10 bg-sky-50 border-dashed border border-sky-400 rounded w-full aspect-[3/2] sm:aspect-[8/3] flex items-center justify-center" id="dropped-receiver">
                <div class='text-center'>
                    <i class="text-sky-400 fas fa-cloud-upload-alt fa-4x opacity-30"></i><br>Drag & drop files here or click here to pick files
                </div>
            </button>
            <div class="w-full pt-5" id="progressbars"></div>
        </div>`);

        const modal = Merapi.createModal('Upload File', body);
        modal.show();


        var r = new Resumable({
            target: opts.endpoint,
            testChunks: false,
            query: { 
                parent: opts.parent
            }
        });

        r.assignBrowse(body.find("#dropped-receiver")[0])
        r.assignDrop(body.find("#dropped-receiver")[0])


        var progressBar = new ProgressBar($('#progressbars'));


        r.on('fileAdded', function (resumableFile, event) {
            console.log("File added: ", resumableFile);
            progressBar.fileAdded(resumableFile);
        });

        // Listen to fileProgress event
        r.on('fileProgress', function (resumableFile) {
            // Update the progress bar for this file
            progressBar.uploading(resumableFile)
        });


        function ProgressBar(ele) {
            this.holder = $(ele);

            this.fileAdded = function (resumableFile) {

                const fileId = resumableFile.uniqueIdentifier;

                var icon = $(`<div class='w-[35px] h-[44px] aspect-square rounded overflow-hidden mr-5 text-center flex items-center justify-center'><i class="fa-regular fa-file-lines fa-2x"></i></div>`);
                const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];

                if (validImageTypes.includes(resumableFile.file.type)) {
                    const objectUrl = URL.createObjectURL(resumableFile.file);
                    icon.html($(`<img src="${objectUrl}" class="bg-black/50 w-full h-full object-cover" />`));
                }

                const itemView = $(`<div id="item-${fileId}" class='mb-4 bg-blue-500/20 py-3 pl-3 pr-2 rounded flex'></div>`)
                    .append(icon)
                    .append($(`<div class='w-full'><p class='text-sm'>${resumableFile.fileName}</p></div>`)
                        .append($(`<div class="bg-white rounded-xl shadow-sm overflow-hidden mt-2">
                                <div id="progress-${fileId}" class="relative h-5 flex items-center justify-center">
                                <div id="progress_bar-${fileId}" class="absolute top-0 bottom-0 left-0 rounded-lg w-[0%] bg-blue-200"></div>
                                <div id="progress_text-${fileId}" class="relative text-blue-900 font-medium text-sm">0%</div>
                                </div>
                            </div>`)))

                this.holder.prepend(itemView);
                r.upload();
            }

            this.uploading = function (file) {

                const fileId = file.uniqueIdentifier;

                const progressBar = $(`#progress_bar-${fileId}`);
                const progressText = $(`#progress_text-${fileId}`);

                var progress = Math.floor(file.progress() * 100);

                if (progressBar) progressBar.css({ width: `${progress}%` });
                if (progressText) progressText.text(`${progress}%`);

                if (progress == 100) {
                    progressBar.css({ width: `100%`, background: `#2dd0b2` });
                    progressText.text(`success`);
                }
            }

            this.finish = function () {
                //(this.holder).addClass('hide').find('.progress-bar').css('width', '0%');
            }
        }
    })
}

// assign to global
window.FileManager = FileManager;