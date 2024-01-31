
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

function formatDate(time) {
    const date = new Date(time);

    const year = date.getFullYear();
    const month = date.getMonth() + 1; // getMonth() returns 0-11
    const day = date.getDate();

    const hours = date.getHours();
    const minutes = date.getMinutes();
    const seconds = date.getSeconds();

    // Pad single digit minutes and seconds with a leading zero
    const paddedMonth = month.toString().padStart(2, '0');
    const paddedDay = day.toString().padStart(2, '0');
    const paddedHours = hours.toString().padStart(2, '0');
    const paddedMinutes = minutes.toString().padStart(2, '0');
    const paddedSeconds = seconds.toString().padStart(2, '0');

    return `${year}-${paddedMonth}-${paddedDay} ${paddedHours}:${paddedMinutes}:${paddedSeconds}`;
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

    const body = $(`<div><small>Parent folder: <b>${opts.parent == '' ? '/' : opts.parent}</b></small><input class='text-input' placeholder="Enter folder name"/></div>`)
    const modal = merapi.createModal('Create New Folder', body);

    return new Promise((resolve, reject) => {

       
        modal.setAction('+', {
            text: "Create",
            class: "btn btn-primary",
            callback: function () {
            
                let fm = new FormData();
                fm.append("name", body.find('input').val());
                fm.append("parent", opts.parent);

                merapi.http.post(opts.endpoint, fm).then((res, status, xhr) => {
                    if (xhr.status === 200) {
                        merapi.toast(res.message, 5, 'text-success');
                        resolve(res)
                    } else {
                       merapi.toast(res.message, 5, 'text-danger');
                        reject(res)
                    }
                }).catch((err) => {
                    merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
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

        merapi.dialog.confirmDanger("Confirm deletion", `<p>Are you sure you want to delete this ${opts.type}?<br/><i>${opts.file}</i></p>This action cannot be undone!!!.`)
            .then((result) => {
                if (result) {
                    const fm = new FormData();
                    fm.append("file", opts.file);

                    merapi.http.post(args.endpoint, fm).then((res, status, xhr) => {
                        if (xhr.status === 200) {
                            merapi.toast(res.message, 5, 'text-success');
                            resolve(res);
                        } else {
                            merapi.toast(res.message, 5, 'text-danger');
                            reject(res);
                        }
                    }).catch((err) => {
                        merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
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
        const modal = merapi.createModal(`Rename ${opts.type}`, element, {
            positive: {
                text: 'Rename',
                class: 'btn btn-primary',
                callback: () => {
                    const fm = new FormData();
                    fm.append("file", opts.file);
                    fm.append("old_name", opts.name);
                    fm.append("new_name", element.find("input").val())
                    merapi.http.post(opts.endpoint, fm).then((res, status, xhr) => {
                        if (xhr.status === 200) {
                            merapi.toast(res.message, 5, 'text-success');
                            resolve(res)
                        } else {
                            merapi.toast(res.message, 5, 'text-danger');
                            reject(res)
                        }
                    }).catch(err => {
                        merapi.toast(err.message ?? err.statusText, 5, 'text-danger');
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

        const modal = merapi.createModal('Upload File', body);
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

        // Add event listeners
        r.on('fileSuccess', function (fileObj, response) {
            // Handle server response here
            progressBar.finish(fileObj, response);
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

            this.uploading = function (resumableFile) {

                const fileId = resumableFile.uniqueIdentifier;

                const progressBar = $(`#progress_bar-${fileId}`);
                const progressText = $(`#progress_text-${fileId}`);

                var progress = Math.floor(resumableFile.progress() * 100);

                if (progressBar) progressBar.css({ width: `${progress}%` });
                if (progressText) progressText.text(`${progress}%`);
            }

            this.finish = function (resumableFile, serverResponse) {


                const fileId = resumableFile.uniqueIdentifier;
                const progressBar = $(`#progress_bar-${fileId}`);
                const progressText = $(`#progress_text-${fileId}`);
                progressBar.css({ width: `100%`, background: `#2dd0b2` });
                progressText.text(`success`);


                if (!FileManager.container.data) FileManager.container.data = [];

                const response = JSON.parse(serverResponse);
                const data = {
                    name: response.data.name,
                    size: response.data.size,
                    type: response.data.type,
                    time: response.data.time,
                    path: response.data.path.full,
                };
                const key = FileManager.container.data.length;

                FileManager.container.data.push(data)

                const element = $(`<div data-fm-key="${key}" onclick="merapi.FileManager.ItemFocusHandle(this);" class="w-[140px] cursor-pointer [&amp;.active>.file-info]:to-slate-500/80 [&amp;.active>.file-info]:text-white [&amp;.active>.hidden]:block [&amp;.active]:shadow-blue-500 bg-gray-200 rounded shadow aspect-[3/4] flex items-top justify-center relative active">
                    <img ${(response.data.icon.scale == 'scale-down' ? "width='35px' height='35px'" : "")} class="rounded overflow-hidden img-${response.data.icon.scale}" src="${response.data.icon.src}">          
                    <div class="file-info absolute rounded-b overflow-hidden pt-5 bottom-0 w-full p-2 bg-gradient-to-b from-transparent from-0% to-slate-500/40 to-50% bg-opacity-20 text-sm">${data.name}</div>
                    <div class="absolute hidden right-2 top-0 z-20">
                        <div class="dropdown">
                            <button data-act-trigger="dropdown" class="dropdown-toggle">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu text-sm">
                                <li class="dropdown-item">
                                    <a type="button" href="?directory=${response.data.path.relative}">
                                        <i class="w-[20px] opacity-70 text-center fa-solid fa-up-right-from-square"></i>
                                        Open file                                        </a>
                                </li>
                                <li class="dropdown-item">
                                    <button type="button" class="" onclick="infoFile('${key}')">
                                        <i class="w-[20px] opacity-70 text-center fa-solid fa-info"></i> File info
                                    </button>
                                </li>
                                <li class="dropdown-item">
                                    <button type="button" class="" onclick="renameFile({ file: '${response.data.path.relative}', name: '${data.name}', type: 'file' })">
                                        <i class="w-[20px] opacity-70 text-center fa-solid fa-pen"></i> Rename
                                    </button>
                                </li>
                                <li class="dropdown-item">
                                    <button type="button" class="text-red-500" onclick="deleteFile({ file: '${response.data.path.relative}', type: 'file' })">
                                        <i class="w-[20px] opacity-70 text-center fa-solid fa-trash"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>`);

                $("#container-filemanager").find('#filemanager-directory-empty').remove();
                $("#container-filemanager").append(element);

            }
        }
    })
}

// assign to global
merapi.assign('FileManager', FileManager)
// window.FileManager = FileManager;