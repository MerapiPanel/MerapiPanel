import { __MP } from "../../../../Buildin/src/main";
import HugeUploader from 'huge-uploader';
import { ModalTypeInterface } from "@il4mb/merapipanel/modal";


const __: __MP = (window as any).__;
const FileManager = __.FileManager;

FileManager.Modal = "CreateProperty";
FileManager.AssetView = "CreateProperty";
FileManager.on("set:Modal", (value: any) => {
    throw new Error("Error: Can't change FileManager object");
});
FileManager.on("set:endpoints", (value: any) => {
    throw new Error("Error: Can't change FileManager object");
});


type Asset = {
    name: string,
    path: string,
    time: number,
    type: "image" | "video" | "audio" | "document" | "other" | "folder",
    children_count?: number,
    [key: string]: any
}
FileManager.config = {
    ...{
        enable_select_folder: true,
        enable_upload: true,
        enable_create_folder: true,
        enable_delete: true,
        enable_rename: true,
        enable_download: true,
        enable_view: true,
        extensions: ["img", "jpeg", "jpg", "png", "gif", "svg", "webp"],
    },
    ...FileManager.config
}
FileManager.roles = {
    ...{
        upload: false,
        modify: false,
    },
    ...FileManager.roles
}

FileManager.uploadInfo = function (id: string) {
    return new Promise((resolve, reject) => {
        __.http.post(FileManager.endpoints.uploadInfo, { id: id }).then(res => {
            resolve(res.data)
        })
            .catch(reject)
    })
}
// upload handler
FileManager.uploadHandler = function (files: File[], appendTo: JQuery<HTMLElement>) {

    if (!FileManager.roles.upload) {
        __.toast("You don't have permission to upload files", 5, 'text-danger');
        return;
    }
    FileManager.fire("upload", files);
    FileManager.upload_files.push(...files);

    window.onbeforeunload = function (e: any) {
        return "Upload is in progress. Are you sure you want to leave?";
    };


    for (let i = 0; i < files.length; i++) {

        const file = files[i];
        const component = $(`<div class='card rounded-3 shadow-sm border-0'>`)
            .append($(`<small class="text-muted p-1 pb-0 d-inline-block file-name text-break" >${files[i].name}</small>`)
                .append($(`<span class="text-muted p-1 d-inline-block fw-semibold file-size" >${(file.size / 1024 / 1024).toFixed(2)} MB</span>`)))

            .append(
                (file as any).folder
                    ? `<small class='d-block text-muted p-1 pt-0 mb-1' style="font-size: 0.7em">folder: ${(file as any).folder || "/"}</small>`
                    : "")
            .append(
                $(`<div class="d-flex align-items-end">`).append(
                    $(`<div class="progress rounded-0 flex-grow-1" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">`)
                        .css("height", "19px")
                        .append(`<div class="progress-bar rounded-0" style="width: 5%">5%</div>`),
                    $(`<button type="button" class="btn btn-sm btn-warning py-0 px-1 rounded-0 ms-1">Pause</button>`)

                )

            )
        appendTo.prepend(component);

        let uploadURL: string = FileManager.endpoints.upload;
        if (!uploadURL) {
            __.toast("Error: Please set FileManager.endpoints.upload", 5, 'text-danger');
            return;
        }


        const uploader = new HugeUploader({
            endpoint: uploadURL,
            file: files[i],
            headers: {
                "uploader-file-name": files[i].name,
                "uploader-file-folder": (file as any).folder,
                "uploader-file-size": files[i].size
            },
            chunkSize: 2
        });

        const $btn = component.find(".btn");
        const $progressBar = component.find(".progress-bar");

        function pauseUpload() {
            uploader.togglePause();
            if (uploader.paused) {
                $btn.text("Resume").removeClass("btn-warning btn-success").addClass("btn-success");
                $progressBar.removeClass("bg-danger bg-success progress-bar-striped progress-bar-animated").addClass("bg-warning");
            } else {
                $btn.text("Pause").removeClass("btn-success btn-danger").addClass("btn-warning");
                $progressBar.removeClass("bg-danger bg-success bg-warning").addClass("bg-primary progress-bar-striped progress-bar-animated");
            }
            $btn.off("click", pauseUpload).off("click", tryAgain).on("click", pauseUpload);
        }

        function tryAgain() {
            if (uploader.paused) {
                pauseUpload();
            } else {
                pauseUpload();
                pauseUpload();
            }
        }

        component.find(".btn").on("click", pauseUpload);

        // subscribe to events
        uploader.on('error', (err) => {
            $progressBar.css("width", "100%").text(err.detail).addClass("bg-danger").removeClass("bg-primary progress-bar-striped progress-bar-animated");
            component.find(".file-name").removeClass("text-muted").addClass("text-danger");
            component.find(".btn").off("click", pauseUpload).text("Try Again").removeClass("btn-warning btn-success").addClass("btn-danger").on("click", tryAgain);
            __.toast(err.detail || file.name + " upload failed", 5, 'text-danger');
        });

        uploader.on('progress', (progress) => {
            $progressBar.removeClass("bg-danger bg-success bg-warning").addClass("bg-primary progress-bar-striped progress-bar-animated").css("width", progress.detail + "%").text(progress.detail + "%");
            component.find(".file-name").removeClass("text-danger text-warning").addClass("text-muted");
            if (uploader.paused) {
                $btn.text("Resume").removeClass("btn-warning btn-success").addClass("btn-success");
                $progressBar.removeClass("bg-danger bg-success progress-bar-striped progress-bar-animated").addClass("bg-warning");
            }
            if (progress.detail == 100) {
                $btn.off("click", pauseUpload).off("click", tryAgain).html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading...`).removeClass("btn-warning btn-success").addClass("btn-primary");
                $progressBar.html("Processing...").removeClass("bg-danger bg-success bg-warning progress-bar-striped progress-bar-animated").addClass("bg-primary");
            }
        });

        uploader.on('finish', (body: any) => {

            FileManager.uploadInfo(uploader.headers['uploader-file-id'])
                .then((info: any) => {
                    FileManager.fire("upload:finish", info);
                })
                .catch((err: any) => {
                    __.toast(err.message || "Something went wrong", 5, 'text-danger');
                })

            setTimeout(() => {
                $progressBar.css("width", "100%").text("100%").addClass("bg-success").removeClass("bg-primary bg-danger bg-warning progress-bar-striped progress-bar-animated");
                component.find(".file-name").removeClass("text-muted").addClass("text-success");
                $btn.off("click", pauseUpload).off("click", tryAgain).text("Remove").removeClass("btn-warning btn-danger btn-success").addClass("btn-primary")
                    .on("click", () => {
                        component.remove();
                        let index = FileManager.upload_files.indexOf(files[i]);
                        if (index > -1) {
                            FileManager.upload_files.splice(index, 1);
                        }
                    });
                __.toast(file.name + " uploaded successfully!", 5, 'text-success');
            }, 400);
            setTimeout(() => {
                component.fadeOut({
                    duration: 3000,
                    complete: () => {
                        component.remove();
                        let index = FileManager.upload_files.indexOf(files[i]);
                        if (index > -1) {
                            FileManager.upload_files.splice(index, 1);
                        }
                    }
                })
            }, 60000);

        });

        uploader.on('offline', () => {
            __.toast("Network offline", 5, 'text-danger');
        });

    }
}

FileManager.on("upload:finish", (files: File[]) => {
    if (files.length > 0) {
        $("#floating_button").find(".badge").text(files.length);
    } else {
        window.onbeforeunload = null;
        $("#floating_button").fadeOut({
            duration: 1000,
            complete: () => $("#floating_button").remove()
        })
    }
})

FileManager.upload_files = [];

// show upload manager
FileManager.upload = function (files: File[] = []) {
    if (!FileManager.roles.upload) {
        __.toast("You don't have permission to upload files", 5, 'text-danger');
        return;
    }
    let folder: any = null;
    if (FileManager.selectedFolder) {
        folder = FileManager.selectedFolder.path || null;
    }
    if (!folder && FileManager.config.enable_select_folder) {
        folder = "/";
    }

    const $content = $("<div>")
        .css({
            display: "flex",
            flexWrap: "wrap",
            flexDirection: "column",
            gap: "1rem"
        })
        .append(
            $(`<div id="upload">`)
                .css({
                    position: 'relative',
                    flex: 1,
                    minHeight: "250px",
                    minWidth: "250px",
                    borderRadius: "5px",
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    padding: "10px",
                    background: "#0389fff0",
                    color: "#fff",
                    opacity: 0.5
                })
                .append(
                    $(`<div class="w-100">`).css({ position: "absolute", top: "10px", left: "10px" }).append(folder ? $(`<h5 id="upload-path">`).text("upload to: " + folder) : ""),
                    $("<div class='text-center'>")
                        .on("click", () => {
                            $(`<input type="file" multiple>`).on('input', (e) => FileManager.upload((e.target as any).files, $(`#upload-content`))).trigger("click");
                        })
                        .on("drop", (e) => {
                            e.preventDefault();
                            console.log(e);
                        })
                        .css({
                            margin: "auto 0px",
                            width: "100%",
                            minHeight: "250px",
                            display: "flex",
                            justifyContent: "center",
                            alignContent: "center",
                            alignItems: "center",
                            flexDirection: "column",
                        })
                        .append(
                            $(`<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                        </svg>`),
                            $(`<h5 class="text-center mt-3">`).text("Click or Drop Files Here"),
                        )
                )
        )
        .append(
            $(`<div class="d-flex flex-column gap-1" id="upload-content">`)
        );




    const modal = $("#FileManagerUploadModal").length ? __.modal.from($("#FileManagerUploadModal")) : __.modal.create("Upload Files", $content);
    modal.el.prop("id", "FileManagerUploadModal");
    modal.find(".modal-footer").remove();
    modal.show();

    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            (files[i] as any).folder = folder;
        }
        FileManager.uploadHandler(files, $(modal.el).find("#upload-content"));
    }


    function modalHideHandler() {

        $("#floating_button").remove();
        if (FileManager.upload_files.length > 0) {
            let floating_button = $("<button id=\"floating_button\">")
                .append(`<i class="fa-solid fa-cloud-arrow-up fa-xl"></i>`)
                .append(`<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${FileManager.upload_files.length}</span>`)
                .on("click", () => {
                    modal.show();
                    floating_button.remove();
                })
                .css({
                    position: "fixed",
                    bottom: "20px",
                    right: "20px",
                    background: "#2685ff",
                    color: "#fff",
                    border: "none",
                    cursor: "pointer",
                    width: "30px",
                    height: "30px",
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center",
                    borderRadius: "2rem",
                    padding: "1.5rem",
                    boxShadow: "0 0 10px 0 rgba(0, 0, 0, 0.2)",
                    zIndex: 9999,
                    transition: "1s",
                    opacity: 0,
                    transform: "scale(5)",
                });
            $(document.body).append(floating_button);
            setTimeout(() => {
                floating_button.css({
                    transform: "scale(1)",
                    opacity: 1
                });
            }, 100);
        }
    }

    modal.off("modal:hide", modalHideHandler);
    modal.on("modal:hide", modalHideHandler);
}


FileManager.delete = function (asset: Asset) {
    __.dialog.danger("Are you sure?", `<p>Are you sure you want to delete <span class="text-danger">${asset.path}</span>?</p>`)
        .then(() => {
            const deleteURL = FileManager.endpoints.delete;
            if (!deleteURL) {
                __.toast("Error: Please set FileManager.endpoints.delete", 5, 'text-danger');
            }
            __.http.post(deleteURL, { path: asset.path })
                .then(res => {
                    __.toast("File deleted successfully", 5, 'text-success');
                    FileManager.fire("delete", asset);
                    FileManager.fire(`delete:${asset.path}`, asset);
                })
                .catch(err => {
                    __.toast(err.message, 5, 'text-danger');
                })
        })
}



FileManager.rename = function (asset: Asset) {
    const content = $(`<div class="form-group mb-3">`)
        .append(
            $(`<label>File Name</label>`),
            $(`<input type="text" class="form-control" value="${asset.name}" />`),
        );
    setTimeout(() => {
        content.find("input").focus();
    }, 400);
    __.dialog.confirm("Rename " + asset.name, content)
        .then(function () {
            const name = content.find("input").val();
            if (name && name.length > 0) {
                const renameURL = FileManager.endpoints.rename;
                if (!renameURL) {
                    __.toast("Error: Please set FileManager.endpoints.rename", 5, 'text-danger');
                }
                __.http.post(renameURL, { path: asset.path, name: name })
                    .then(res => {
                        __.toast("File renamed successfully", 5, 'text-success');
                        FileManager.fire("rename", asset, name);
                        FileManager.fire(`rename:${asset.path}`, asset, name);
                    })
                    .catch(err => {
                        __.toast(err.message, 5, 'text-danger');
                    });
            } else {
                __.toast("File name cannot be empty", 5, "text-danger");
            }
        })
}

FileManager.newFolder = function (asset: Asset) {
    const content = $(`<div class="form-group mb-3">`)
        .append(
            $(`<label>Folder Name</label>`),
            $(`<input type="text" class="form-control" />`),
        );
    setTimeout(() => {
        content.find("input").focus();
    }, 400);
    __.dialog.confirm("Create new folder", content)
        .then(function () {
            const name = content.find("input").val();
            if (name && name.length > 0) {
                const newFolderURL = FileManager.endpoints.newFolder;
                if (!newFolderURL) {
                    __.toast("Error: Please set FileManager.endpoints.newFolder", 5, 'text-danger');
                }
                __.http.post(newFolderURL, { path: asset.path, name: name })
                    .then(res => {
                        __.toast("Folder created successfully", 5, 'text-success');
                        FileManager.fire("newFolder", asset, name);
                        FileManager.fire(`newFolder:${asset.path}`, asset, name);
                    })
                    .catch(err => {
                        __.toast(err.message, 5, 'text-danger');
                    });
            } else {
                __.toast("Folder name cannot be empty", 5, "text-danger");
            }
        })

}




FileManager.assets = {};
function storeAsset(assets: any[]) {
    for (const asset of assets) {
        let keyPath = (asset.path || "").split("/").filter(e => e !== "");
        if (keyPath.length > 1) {
            let currentAssets = FileManager.assets;
            for (let i = 0; i < keyPath.length; i++) {
                const key = keyPath[i];
                if (!currentAssets[key]) {
                    currentAssets[key] = {
                        path: keyPath.slice(0, i + 1).join("/"),
                        children: {}
                    };
                }
                if (i === keyPath.length - 1) {
                    // This is the last key in the path, store additional data if needed
                    currentAssets[key] = { ...{ children: {} }, ...asset }; // Assuming asset contains additional data
                } else {
                    // Update the current assets to point to the children for the next iteration
                    currentAssets = currentAssets[key].children;
                }
            }
        }
    }
}
function getAsset(path: string) {
    let result = null;
    let currentAssets = FileManager.assets;
    let keyPath = (path || "").split("/").filter(e => e !== "");
    for (let i = 0; i < keyPath.length; i++) {
        const key = keyPath[i];
        if (currentAssets[key]) {
            if (i === keyPath.length - 1) {
                result = currentAssets[key];
            } else {
                currentAssets = currentAssets[key].children;
            }
        } else {
            break;
        }
    }
    return result;
}
function appendAsset(path: string, asset: any) {

    let currentAssets = FileManager.assets;
    let parent = currentAssets;
    let keyPath = (path || "").replace(/^\//, "").split("/").filter(e => e !== "");

    for (let i = 0; i < keyPath.length; i++) {
        const key = keyPath[i];

        if (i === keyPath.length - 1) {
            if (currentAssets[key]) {
                const children = currentAssets[key].children || {};
                children[keyPath[i]] = asset;
                currentAssets[key].children = children;
            } else {
                currentAssets[key] = { ...{ children: {} }, ...asset };
            }
            parent.children_count += 1;
            // This is the last key in the path, store additional data if needed
        } else {
            if (!currentAssets[key]) {
                currentAssets[key] = {
                    name: key,
                    path: keyPath.slice(0, i + 1).join("/"),
                    type: "folder",
                    children: {},
                    children_count: 0,
                    time: Date.now()
                }
                parent.invalid = true;
            }
            parent = currentAssets[key];
            // Update the current assets to point to the children for the next iteration
            currentAssets = currentAssets[key].children;

        }
    }

    return true;
}

function replaceAsset(old_asset: any, new_asset: any) {
    let status = false;
    let currentAssets = FileManager.assets;
    let keyPath = (old_asset.path || "").replace(/^\//, "").split("/").filter(e => e !== "");
    for (let i = 0; i < keyPath.length; i++) {
        const key = keyPath[i];
        if (currentAssets[key]) {
            if (i === keyPath.length - 1) {
                const children = currentAssets[key].children || {};
                delete currentAssets[key];
                currentAssets[new_asset.name] = { ...{ children: children }, ...new_asset };
                status = true;
                break;
            } else {
                currentAssets = currentAssets[key].children;
            }
        } else {
            break;
        }
    }

    return status;
}

function deleteAsset(asset: Asset) {

    let status = false;
    let currentAssets = FileManager.assets;
    let parent = currentAssets;
    let keyPath = (asset.path || "").replace(/^\//, "").split("/").filter(e => e !== "");
    for (let i = 0; i < keyPath.length; i++) {
        const key = keyPath[i];
        if (currentAssets[key]) {
            if (i === keyPath.length - 1) {
                delete currentAssets[key];
                if (parent && parent.children_count) {
                    parent.children_count -= 1;
                }
                status = true;
                break;
            } else {
                parent = currentAssets[key];
                currentAssets = currentAssets[key].children;
            }
        } else {
            break;
        }
    }

    return status;
}



FileManager.selectedFolder = null;

FileManager.on("property:Modal", (e, Modal: any) => {

    const icons: any = {
        "image": `<i class="fa-solid fa-image fa-xl"></i>`,
        "video": `<i class="fa-solid fa-video fa-xl"></i>`,
        "audio": `<i class="fa-solid fa-music fa-xl"></i>`,
        "document": `<i class="fa-solid fa-file fa-xl"></i>`,
        "other": `<i class="fa-solid fa-file fa-xl"></i>`,
        "folder": `<i class="fa-solid fa-folder fa-xl"></i>`,
        "file": `<i class="fa-solid fa-file fa-xl"></i>`
    }


    function truncateStringFromStart(str: string, maxLength: number) {
        if (str.length > maxLength) {
            return "..." + str.substring(str.length - maxLength + 3);
        } else {
            return str;
        }
    }
    function clickOutsideHandler(e: any) {

        if (!FileManager.config.enable_select_folder) return false;
        if ($(e.target).closest('#assets-content').length > 0 || $(e.target).closest('.file-asset').length > 0 || $(e.target).closest('#upload').length > 0 || $(e.target).closest('#FileManagerUploadModal').length > 0) {
            return false; // if you want to ignore the click completely
            // return; // else
        }
        FileManager.selectedFolder = "/";
        $("#FileManagerModal").find(".file-asset").css({ backgroundColor: "transparent" });
        $("#FileManagerModal").find("#upload-path").text(`upload to: /`);

    }

    function selectedHandler(e: Event, asset: Asset) {

        $("#FileManagerModal").find(".file-asset").css({ backgroundColor: "transparent" });
        $("#FileManagerModal").find("#" + assetId(asset)).css({ backgroundColor: "rgba(145, 209, 255, 0.1)" });
        $("#FileManagerModal").find("#upload-path").text(`upload to: ${asset.path.replace(/^[/]content/, '')}`);
        $("#FileManagerModal").off("click", clickOutsideHandler).on("click", clickOutsideHandler);
    }

    function assetId(asset: Asset) {
        return ("asset-" + asset.path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-");
    }

    function uploadFinishHandler(e: Event, fileInfo: any) {
        const path = fileInfo.file_folder;
        const name = fileInfo.file_name;
        const type = fileInfo.file_type;
        if (appendAsset(path.replace(/\/$/, "") + "/" + name, {
            name: name,
            path: path.replace(/\/$/, "") + "/" + name,
            type: type,
            children_count: 0,
            time: new Date().getTime()
        })) {
            if (path.replace(/^[/]content\//, '') == "") {
                Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
            } else {
                let _path = path;
                let collapse = $("#collapse-" + ("asset-" + _path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-"));
                if (collapse.length) {
                    Modal.renderAsset(collapse, path);
                } else {
                    while (collapse.length == 0) {
                        _path = _path.replace(/\/[^\/]+$/, '');
                        collapse = $("#collapse-" + ("asset-" + _path.replace(/[^a-zA-Z0-9]/g, "-")).replace(/-+/g, "-"));
                    }
                    Modal.renderAsset(collapse, _path);
                }
            }
        }
    }


    function showContextMenu(e: Event, asset: Asset) {

        if (!FileManager.roles.modify) {
            return;
        }

        const config = FileManager.config;
        if (config.enable_context_menu == false) return false;
        if (!config.enable_view && !config.enable_rename && !config.enable_new_folder && !config.enable_download && !config.enable_delete) return false;

        function adjustContentPosition(content: HTMLElement) {
            var rect = content.getBoundingClientRect();
            var isInViewport =
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth);

            if (!isInViewport) {
                var newX = rect.left < 0 ? 0 : rect.right > window.innerWidth ? window.innerWidth - rect.width : rect.left;
                var newY = rect.top < 0 ? 0 : rect.bottom > window.innerHeight ? window.innerHeight - rect.height : rect.top;

                content.style.left = newX + "px";
                content.style.top = newY + "px";
            }
        }

        function hideContextMenu() {
            $("#filemanager-context-menu").remove();
            $(`#${assetId(asset)}`).removeClass("bg-primary bg-opacity-10");
            $(document).off("click", clickOutsideHandler);
        }

        function clickOutsideHandler(e) {
            if (!$(e.target).closest('#filemanager-context-menu').length) {
                hideContextMenu();
            }
        }
        $(".file-asset").removeClass("bg-primary bg-opacity-10");
        $(`#${assetId(asset)}`).addClass("bg-primary bg-opacity-10");
        $("#filemanager-context-menu").remove();

        const content = $(`<ul class="list-group list-group-flush" id="filemanager-context-menu">`)
            .append(
                $(`<li class="list-group-item fw-semibold px-3 py-2">${icons[asset.type]} /${truncateStringFromStart(asset.path.replace(/^[/]content\//, ""), 22).replace(/^\//, "")}</li>`),
                asset.type !== "folder" && config.enable_view
                    ? $(`<li class="list-group-item "><i class="fa-regular fa-folder-open"></i> Open</li>`)
                        .on("mouseenter", function () {
                            $(this).addClass("bg-primary text-white");
                        }).on("mouseleave", function () {
                            $(this).removeClass("bg-primary text-white");
                        }).on("click", function () {
                            hideContextMenu();
                            FileManager.AssetView.show(asset);
                        }) : "",
                asset.path != "/" && config.enable_rename ? $(`<li class="list-group-item"><i class="fa-solid fa-pen-to-square"></i> Rename</li>`)
                    .on("mouseenter", function () {
                        $(this).addClass("bg-primary text-white");
                    })
                    .on("mouseleave", function () {
                        $(this).removeClass("bg-primary text-white");
                    })
                    .on("click", function () {
                        hideContextMenu();
                        FileManager.rename(asset);
                    }) : "",
                asset.type == "folder" && config.enable_new_folder ? $(`<li class="list-group-item"><i class="fa-solid fa-folder-plus"></i> Folder</li>`)
                    .on("mouseenter", function () {
                        $(this).addClass("bg-primary text-white");
                    })
                    .on("mouseleave", function () {
                        $(this).removeClass("bg-primary text-white");
                    })
                    .on("click", function () {
                        hideContextMenu();
                        FileManager.newFolder(asset);
                    })
                    : "",
                asset.type !== "folder" && config.enable_download ? $(`<li class="list-group-item"><i class="fa-solid fa-download"></i> Download</li>`)
                    .on("mouseenter", function () {
                        $(this).addClass("bg-primary text-white");
                    })
                    .on("mouseleave", function () {
                        $(this).removeClass("bg-primary text-white");
                    })
                    .on("click", () => {
                        hideContextMenu();
                        const a = $('<a>').prop('download', asset.name).prop('target', '_blank').attr('href', window.location.origin + asset.path);
                        a[0].click();
                    }) : "",
                asset.path != "/" && config.enable_delete ? $(`<li class="list-group-item"><i class="fa-solid fa-trash"></i> Delete</li>`)
                    .on("mouseenter", function () {
                        $(this).addClass("bg-danger text-white");
                    })
                    .on("mouseleave", function () {
                        $(this).removeClass("bg-danger text-white");
                    })
                    .on("click", function () {
                        hideContextMenu();
                        FileManager.delete(asset);
                    }) : ""
            )
        content.css({ position: 'absolute', zIndex: 600, left: (e as any).pageX - 200, top: (e as any).pageY, boxShadow: '0 0 10px 0 rgba(0, 0, 0, 0.1)', cursor: 'pointer', width: "100%", maxWidth: "200px" }).appendTo("body");
        adjustContentPosition(content[0]);
        $(document).on("click", clickOutsideHandler);
    }

    function renameHandler(e: Event, asset: Asset, name: string) {
        const paths = asset.path.split("/");
        paths[paths.length - 1] = name;
        if (replaceAsset(asset, { name: name, path: paths.join("/"), type: asset.type, children_count: asset.children_count, time: asset.time })) {
            Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
        }
    }

    function deleteHandler(e: Event, asset: Asset) {

        if (deleteAsset(asset)) {
            $(`#${assetId(asset)}`).remove();
        }
    }


    function newFolderHandler(e: Event, asset: Asset, name: string) {

        const newAsset = {
            name: name,
            path: asset.path.replace(/\/$/, "") + "/" + name + "/",
            type: "folder",
            children_count: 0,
            time: Date.now(),
        }

        if (appendAsset(asset.path, newAsset)) {
            if (asset.path.replace(/^[/]content\//, '') == "") {
                Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
            } else {
                const id = "#collapse-" + assetId(asset);
                const collapse = $("#FileManagerModal").find(id);
                if (collapse.length) {
                    Modal.renderAsset(collapse, asset.path);
                }
            }
        }


    }

    Modal.show = function () {

        const $content = $("<div>")
            .css({
                display: "flex",
                flexWrap: "wrap",
                alignItems: "start",
                gap: "10px",
                minHeight: "400px",
            })
            .append(
                FileManager.roles.upload && FileManager.config.enable_upload ?
                    $(`<div id="upload">`)
                        .css({
                            position: 'relative',
                            flex: "1 1 300px",
                            minHeight: "200px",
                            width: "100%",
                            border: "1px solid #eee",
                            borderRadius: "5px",
                            display: 'flex',
                            flexDirection: 'column',
                            justifyContent: 'center',
                            alignItems: 'center',
                            padding: "10px",
                            cursor: "pointer"
                        })
                        .append(
                            $(`<div class="w-100">`).append(
                                FileManager.config.enable_select_folder
                                    ? $(`<h5 id="upload-path">upload to: /</h5>`)
                                    : $(`<h5 id="upload-path"></h5>`)
                            ),

                            $("<div class='text-center'>")
                                .on("click", () => {
                                    const input = $(`<input type="file" multiple>`).on('input', (e) => { if (!e.target || !(e.target as any).files) return; FileManager.upload((e.target as any).files); });
                                    if (FileManager.config.extensions) {
                                        let extensions: string[] = [];
                                        Object.values(FileManager.config.extensions).forEach(ext => {
                                            if (typeof ext === 'string' || ext instanceof String) {
                                                if (ext.startsWith(".")) {
                                                    ext.replace(/^./, "");
                                                }
                                                extensions.push(ext as string);
                                            }
                                        });
                                        input.attr("accept", extensions.join(", "));
                                    }
                                    input.trigger("click");
                                })
                                .on("drop", (e) => {
                                    e.preventDefault();
                                    console.log(e);
                                })
                                .css({
                                    margin: "auto 0px",
                                    width: "100%",
                                    display: "flex",
                                    justifyContent: "center",
                                    alignContent: "center",
                                    alignItems: "center",
                                    flexDirection: "column",
                                })
                                .append(
                                    $(`<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                                </svg>`),
                                    $(`<h5 class="text-center mt-3">`).text("Click or Drop Files Here"),
                                )
                        )
                    : ""
            )
            .append(
                $(`<div class="list-group list-group-flush" id="assets-content">`).css({ maxHeight: "65vh", overflow: "auto", flex: "1 1 300px", minHeight: "50vh" })
                    .on('contextmenu', (e: Event) => {
                        e.preventDefault();
                        showContextMenu(e, {
                            type: "folder",
                            path: "/",
                            name: "root",
                            time: new Date().getTime(),
                        });
                    })
            );


        const modal = $("#FileManagerModal").length ? __.modal.from($("#FileManagerModal")) : __.modal.create("FileManager", $content);
        modal.el.prop("id", "FileManagerModal");
        modal.el.find(".modal-dialog").css({
            maxWidth: "1024px",
        });
        modal.el.find(".modal-body").css({
            maxWidth: "1024px",
        });
        modal.el.find(".modal-footer").remove();

        FileManager.on("asset:select", (e) => {

            setTimeout(() => {
                $(document).find("#FileManagerModal").remove();
            }, 100);

        });


        modal.show();
        Modal.renderAsset($(modal.el).find("#assets-content"), "/");

        FileManager.off("upload:finish", uploadFinishHandler);
        FileManager.on("upload:finish", uploadFinishHandler);


    }


    Modal.renderAsset = function (container: JQuery, folder = "/") {
        container.empty().append(`<div class="text-center p-5"><i class="fa-solid fa-spinner fa-spin"></i></div>`);
        Modal.fetchAssets(folder).then((assets: any[]) => {
            container.empty().append(assets.map((asset: any) => Modal.createAssetDom(asset) as any));
            container.parent(".list-group-item").find("small.children-count").first().text(assets.length);
            if (assets.length == 0) {
                container.html(`<div class="text-center p-4">No assets found</div>`);
            }
        }).catch((err: any) => {
            __.toast(err.message || 'Unknown Error', 5, 'text-danger');
            console.error(err);
        });

        FileManager.off("selectFolder", selectedHandler);
        FileManager.on("selectFolder", selectedHandler);
    }

    Modal.fetchAssets = function (folder = "/") {
        const fetchURL = FileManager.endpoints.fetch;
        return new Promise((resolve, reject) => {

            let assets: any = getAsset(folder == "/" ? "/content" : folder);
            if (assets && assets.children && Object.keys(assets.children).length > 0 && assets.invalid !== true) {
                var result = Object.keys(assets.children).map((key) => assets.children[key]);
                resolve(result);
                return;
            }

            if (assets?.invalid) {
                delete assets.invalid;
            }

            if (!fetchURL) {
                reject("No fetchURL set");
                return;
            }
            const formData = new FormData();
            formData.append("folder", folder);
            if (FileManager.config.extensions) {
                Object.keys(FileManager.config.extensions).forEach(key => {
                    formData.append(`extensions[${key}]`, FileManager.config.extensions[key]);
                })
            }
            __.http.post(fetchURL, formData).then(result => {
                const assets: any[] = (result.data || []) as any;
                storeAsset(assets);
                resolve(assets);
            }).catch(err => {
                reject(err);
            })

        });
    }


    Modal.createAssetDom = function (asset: any) {

        function assetClickHandler() {
            // hideContextMenu();
            $("#filemanager-context-menu").remove();
            if (asset.type == "folder") {
                if (FileManager.config.enable_select_folder) {
                    FileManager.selectedFolder = asset;
                    FileManager.fire("selectFolder", asset);
                }
                const collapse = $(this).parent(".list-group-item").find(`#collapse-${assetId(asset)}`);
                collapse.toggleClass("show");
                if (collapse.hasClass("show")) {
                    $(this).find(".fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-up");
                    Modal.renderAsset($(this).parent(".list-group-item").find(".collapse"), asset.path);
                } else {
                    $(this).find(".fa-angle-up").removeClass("fa-angle-up").addClass("fa-angle-down");
                }
            } else if (FileManager.config.enable_view) {
                FileManager.AssetView.show(asset);
            }
        }

        FileManager.off(`delete:${asset.path}`, deleteHandler);
        FileManager.on(`delete:${asset.path}`, deleteHandler);
        FileManager.off(`rename:${asset.path}`, renameHandler);
        FileManager.on(`rename:${asset.path}`, renameHandler);
        if (asset.type == "folder") {
            FileManager.off(`newFolder:${asset.path}`, newFolderHandler);
            FileManager.on(`newFolder:${asset.path}`, newFolderHandler);
        }

        return $(`<li class="list-group-item file-asset${asset.time > (Math.floor(Date.now() / 1000) - 10) ? ' bg-primary bg-opacity-10' : ''}" id="${assetId(asset)}">`)
            .css({
                "cursor": "pointer",
                background: "transparent",
                userSelect: "none",
                wordWrap: "anywhere"
            })
            .append(
                $(`<div class="d-flex w-100 justify-content-start align-items-center position-relative">`)
                    .append(
                        $(`<div class="asset-icon w-100" style="max-width: 45px;height: 30px; display: flex;justify-content: center;align-items: center; overflow: hidden;">`)
                            .append(
                                asset.type == "image"
                                    ? $(`<img src="${asset.path}" class="img-fluid" style="object-fit: cover;"></div>`)
                                    : icons[asset.type]
                            )
                    )
                    .append(
                        $(`<div class="ms-2 me-1 asset-name">`)
                            .append(
                                $(`<h5 class="mb-0">${asset.name}</h5>`)
                                    .append(
                                        asset.type == "folder"
                                            ? $(`<small class="text-muted text-thin ms-1 position-absolute top-0 children-count">${asset.children_count}</small>`).css({ fontSize: "0.65em" })
                                            : ""
                                    )
                            )
                    )
                    .append(
                        asset.type == "folder"
                            ? $(`<i class="fa-solid fa-angle-down ms-auto"></i>`)
                            : ""
                    )
                    .on("click", assetClickHandler)
                ,
                asset.type == "folder"
                    ? $(`<div class="collapse py-2 list-group list-group-flush" id="collapse-${assetId(asset)}"></div>`)
                    : ""
            )
            .on("contextmenu", (e: Event) => {
                e.preventDefault();
                e.stopPropagation();
                showContextMenu(e, asset);
            })
    }

    FileManager.on("newFolder:/", (e: Event, asset: Asset, name) => {
        console.log(asset, name)
        const newAsset = {
            name: name,
            path: asset.path.replace(/\/$/, "") + "/" + name + "/",
            type: "folder",
            children_count: 0,
            time: Date.now(),
        }
        if (appendAsset("/content", newAsset)) {
            Modal.renderAsset($("#FileManagerModal").find("#assets-content"), "/");
        }
    })
});

FileManager.on("property:AssetView", (e: Event, AssetView: any) => {

    AssetView.show = (asset: any) => {

        const callback = {
            "video": (modal: ModalTypeInterface) => {
                if ($(document.head).find("link#video-js").length == 0) $(document.head).append(`<link id="video-js" href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet">`)
                $.when(
                    $.getScript("https://vjs.zencdn.net/8.10.0/video.min.js")
                ).done(() => {
                    modal.el.find(".asset-view").empty().append(
                        $("<video class='video-js position-absolute w-100 h-100 object-fit-contain' controls preload='auto' id='video'></video>")
                            .attr("src", asset.path)
                    );
                    (window as any).videojs("video", {
                        controls: true,
                        controlBar: {
                            pictureInPictureToggle: false
                        }
                    });

                    function onModalHide() {
                        try {
                            (window as any).videojs("video").dispose();
                        } catch (error) {
                            // console.log(error);
                        }
                        modal.off("modal:hide", onModalHide);
                    }
                    modal.on("modal:hide", onModalHide);
                })



            },
            "audio": (modal: ModalTypeInterface) => {

            },
            "image": (modal: ModalTypeInterface) => {
                modal.el.find(".asset-view").empty().append(
                    $("<img class='w-100 h-100 object-fit-contain' src='" + asset.path + "' />")
                )
            },
            "pdf": (modal: ModalTypeInterface) => {
                $.when(
                    $.getScript("https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js")
                ).done(() => {
                    const pdfjsLib = (window as any).pdfjsLib;
                    const container = $(`<div class="page-container" style="max-height: 65vh; overflow: auto;"></div>`).append(`<canvas class='d-none'></canvas>`);

                    modal.el.find(".asset-view").empty().append(container);


                    // PDF.js worker location
                    const pdfjsWorkerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                    // Initialize PDF.js
                    pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorkerSrc;

                    // Load PDF file
                    const pdfUrl = window.location.origin + asset.path;

                    // Asynchronous download PDF
                    pdfjsLib.getDocument(pdfUrl).promise.then(pdfDoc => {
                        // Iterate over each page of the PDF
                        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                            // Create a container for each page
                            const pageContainer = document.createElement('div');
                            pageContainer.className = 'page-container';
                            container.append(pageContainer);

                            // Get the page
                            pdfDoc.getPage(pageNum).then(page => {
                                // Create a canvas for the page
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                pageContainer.appendChild(canvas);

                                // Set canvas dimensions
                                const viewport = page.getViewport({ scale: 2 });
                                canvas.width = viewport.width;
                                canvas.height = viewport.height;
                                $(canvas).css({
                                    width: "100%",
                                });

                                // Render the page onto the canvas
                                page.render({ canvasContext: ctx, viewport: viewport });
                            });
                        }
                        $('.loding-container').remove();
                    });
                })

            }
        }


        const content = $(`<div class="ratio ratio-16x9 asset-view" style="max-height: 65vh; height: 100%;overflow: hidden;"></div><small class="text-muted text-semibold text-break">${asset.path}</small>`)

        const modal = $("#FileManagerModal_AssetView").length > 0 ? __.modal.from($("#FileManagerModal_AssetView")) : __.modal.create("FileManagerModal_AssetView", content);
        modal.el.prop("id", "FileManagerModal_AssetView");
        modal.el.find(".modal-footer").empty();
        modal.el.find(".modal-title").text(asset.name);
        modal.el.find(".modal-dialog").css({
            maxWidth: "1024px",
        })
        modal.el.find(".modal-body").css({
            maxWidth: "1024px",
        })

        modal.el.find(".asset-view").empty().append(
            $("<div class='w-100 h-100 d-flex justify-content-center align-items-center'>")
                .append(
                    $("<i class='fa-solid fa-spinner fa-pulse fa-lg'></i>"),
                    $("<span class='ms-2'>Loading...</span>")
                )
        )
        modal.show();

        $(modal.el).find(".modal-footer").empty().addClass("p-3").append(
            FileManager.is_pick
                ? $(`<button type="button" class="btn btn-primary ms-2">Select</button>`)
                    .on("click", () => {
                        FileManager.fire("asset:select", asset);
                        modal.el.remove();
                    })
                : ""

        )
        if (callback[asset.type]) {
            callback[asset.type](modal);
        } else {
            if (/\.pdf$/i.test(asset.name)) {
                callback['pdf'](modal);
                return;
            }
            $(modal.el).find(".asset-view").empty().append(`<div class="object-container"><object data="${asset.path}" type="text/html" width="100%" height="100%"></object></div>`);
        }
    }
});


FileManager.is_pick = false;

FileManager.select = function () {
    FileManager.is_pick = true;
    return new Promise((resolve, reject) => {
        function pickFileHandler(e: Event, asset: Asset) {
            FileManager.is_pick = false;
            resolve(asset);
        }
        FileManager.off("asset:select", pickFileHandler);
        FileManager.on("asset:select", pickFileHandler);
        FileManager.Modal.show();

    })
}