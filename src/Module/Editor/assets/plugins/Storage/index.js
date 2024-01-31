// import Merapi from "../../../../../base/assets/merapi";

const StoragePlugin = (editor, args = {}) => {

    function urlWithParams(baseUrl, params) {
        // Parse the existing URL
        const url = new URL(baseUrl);

        // Loop through the params object and set query parameters
        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                url.searchParams.set(key, params[key]);
            }
        }

        return url.toString();
    }


    var storageKey = "editing";
    if (args.id) {
        storageKey += `-${args.id}`;
    }

    editor.on("load", function () {

        console.log("Please wait...");

        editor.on("run:loaded-components", function () {

            if (args.id && args.endpoint) {

                merapi.http.get(urlWithParams(args.endpoint, { id: args.id, editor: 1 })).then(res => {

                    editor.setComponents(res);
                    console.log("Load finish");

                })
            } else if (window.localStorage.getItem(storageKey)) {

                const project = JSON.parse(window.localStorage.getItem(storageKey));
                editor.setComponents(project);
                console.log("Load finish");

            }
        })
    })

    editor.Commands.add('clear-storage', {
        run: (editor) => {
            window.localStorage.removeItem(storageKey);
        }
    })
}

export default StoragePlugin;