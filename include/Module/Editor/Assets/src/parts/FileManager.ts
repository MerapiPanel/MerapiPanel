import * as http from "@il4mb/merapipanel/http";
import { toast } from "@il4mb/merapipanel/toast";

const __ = (window as any).__;


type FileType = {
    name: string
    path: string
    type: string
}

var total = 0, start = 0, limit = 0;
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




    const LoadAssets = (url: string, limit: number, start: number) => {


        return new Promise((resolve, reject) => {
            http.get(url, {
                limit: limit,
                start: start
            })
                .then(res => {
                    resolve(res)
                })
                .catch(err => {
                    reject(err)
                })
        })

    };


    const RenderAssets = (assets: FileType[], container: HTMLElement) => {

        $(container).html("")
            .append(
                $(`<div class="d-flex flex-wrap">`)
                    .append((assets as FileType[]).map(file => {

                        return $(`<div class="card position-relative m-1" style="width: 100%;max-width: 12rem;cursor: pointer;height: 10rem;border: 0;box-shadow: 0 0 4px #eee;overflow: hidden;">`)
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
                    .append(
                        total > assets.length
                            ? $(`<div class="card position-relative m-1 d-flex align-items-center justify-content-center" style="width: 100%;max-width: 12rem;cursor: pointer;height: 10rem;border: 0;box-shadow: 0 0 4px #eee;overflow: hidden;"><span class="text-muted">Load more...</span></div>`)
                                .on("click", () => {

                                    LoadAssets(fetch_url, limit, start + limit)
                                        .then((res: any) => {
                                            total = res.data.total as number;
                                            assets.push(...res.data.items as FileType[]);
                                            RenderAssets(assets, container);
                                        })
                                        .catch(error => {
                                            console.log(error);
                                            toast(error.message || "Internal Server Error", 5, "text-danger");
                                        })
                                })
                            : ""
                    )
            )
    }


    fetch_url = props.am.config.fetch;
    const query: {
        [key: string]: number
    } = getQueryParams(/^(http[s]?:\/\/[^/]+)/.test(fetch_url) ? fetch_url : `${window.location.origin}${fetch_url}`);
    fetch_url = fetch_url.replace(/\?[^/]*/, "");

    limit = query.limit ?? 10;
    start = query.start ?? 0;
    total = 0;
    selectCallback = props.select;
    const assets: FileType[] = [];

    LoadAssets(fetch_url, limit, start)
        .then((res: any) => {
            total = res.data.total as number;
            assets.push(...res.data.items as FileType[]);
            RenderAssets(assets, props.container);
        })
        .catch(error => {
            console.log(error);
            toast(error.message || "Internal Server Error", 5, "text-danger");
        })



}