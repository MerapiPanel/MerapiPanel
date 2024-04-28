import * as http from "@il4mb/merapipanel/http";
import { toast } from "@il4mb/merapipanel/toast";

const __ = (window as any).__;


type FileType = {
    name: string
    path: string
    type: string
}

var fetch_url: string;
var selectCallback: Function | null = null;

export const fileManager = (props) => {


    function getQueryParams(url: string) {
        const urlObj = new URL(url);
        const queryParams = urlObj.searchParams;

        // Create an object to store the query parameters
        const params = {};

        // Iterate over each query parameter
        queryParams.forEach((value, key) => {
            // If the parameter already exists, convert it to an array
            if (params[key]) {
                if (!Array.isArray(params[key])) {
                    params[key] = [params[key]];
                }
                params[key].push(value);
            } else {
                params[key] = value;
            }
        });

        return params;
    }


    function uploadFile(files: File[]) {

        const uploadURL: string = props.am.config.upload;

        for (let i = 0; i < files.length; i++) {

            const component = $(`<div class='card rounded-3 shadow-sm border-0'>`)
                .append(`<small class="text-muted p-1" >${files[i].name}</small>`)
                .append(
                    $(`<div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">`)
                        .append(`<div class="progress-bar" style="width: 25%">25%</div>`)
                )


            $(props.container).find("#upload #progress").append(component)

            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append("files[0]", files[i]);
            xhr.open("POST", uploadURL, true);
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    component.find(".progress-bar").css("width", percent + "%").text(percent + "%");
                }
            };

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.code === 200) {

                            const asset: FileType = {
                                name: response.data[0].name,
                                path: response.data[0].src,
                                type: response.data[0].type
                            };

                            assets = [asset].concat(assets);
                            RenderAssets(assets);

                            component.remove();
                        } else {
                            toast(response.message, 5, "text-danger");
                        }

                        component.remove();
                    } else {
                        toast("Upload failed", 5, "text-danger");
                    }
                }
            };

            xhr.send(formData);
        }
    }



    const LoadAssets = (url: string) => {

        return new Promise((resolve, reject) => {
            http.get(url, {})
                .then(res => {
                    resolve(res)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }


    const RenderAssets = (assets: FileType[]) => {

        $(props.container)
            .find("#assets")
            .html("")
            .append((assets as FileType[]).map(file => {

                return $(`<div class="card position-relative m-1">`)
                    .css({
                        cursor: 'pointer',
                        height: '10rem',
                        border: 0,
                        boxShadow: "0 0 4px #eee",
                        overflow: 'hidden',
                        flex: "45%",
                        minWidth: "10rem"
                    })
                    .append($(`<img src="${file.path}" class="card-img-top w-100 h-100 object-fit-cover" alt="${file.name}">`))
                    .append(
                        $(`<div class="card-body position-absolute bottom-0 px-2 py-1">`)
                            .append(
                                $(`<h5 class="card-title text-white" style="font-size: 0.8rem; filter: drop-shadow(0 0 0.5rem #000000); word-break: break-all;">`).text(file.name),
                            )
                    )
                    .on("click", () => {

                        if (selectCallback) {
                            selectCallback({
                                name: file.name,
                                src: file.path
                            });
                            props.close();
                        }
                    })
            }))
    }


    fetch_url = props.am.config.fetch;
    const query: {
        [key: string]: number
    } = getQueryParams(/^(http[s]?:\/\/[^/]+)/.test(fetch_url) ? fetch_url : `${window.location.origin}${fetch_url}`);
    fetch_url = fetch_url.replace(/\?[^/]*/, "");
    selectCallback = props.select;
    var assets: FileType[] = [];


    $(props.container)
        .html("")
        .css({
            display: "flex",
            flexWrap: "wrap",
            gap: "10px",
        })
        .append(
            $(`<div id="upload">`)
                .css({
                    position: 'relative',
                    flex: 1,
                    minHeight: "250px",
                    minWidth: "250px",
                    border: "1px solid #eee",
                    borderRadius: "5px",
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    padding: "10px",
                })
                .append(
                    $("<div class='text-center'>")
                        .on("click", () => {
                            $(`<input type="file" multiple>`).on('input', (e) => uploadFile((e.target as any).files)).trigger("click");
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
                .append(
                    $(`<div id="progress">`)
                        .css({
                            width: "100%",
                            overflow: "auto",
                            display: "grid",
                            gap: "5px",
                        }),
                ),
        )
        .append(
            $(`<div class="d-flex flex-wrap" id="assets">`)
                .css({
                    maxHeight: "65vh",
                    minWidth: "250px",
                    overflow: "auto",
                    flex: 1
                })
        )



    LoadAssets(fetch_url)
        .then((res: any) => {
            assets.push(...res.data.items as FileType[]);
            RenderAssets(assets);
        })
        .catch(error => {
            console.log(error);
            toast(error.message || "Internal Server Error", 5, "text-danger");
        })
}