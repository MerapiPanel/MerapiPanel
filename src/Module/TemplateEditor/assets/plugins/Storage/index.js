// import Merapi from "../../../../../base/assets/merapi";

const StoragePlugin = (editor, args = {}) => {

    console.log(args);
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

            // console.log(args);

            if (args.id && args.endpoint.fetch) {

                $.ajax({
                    url: urlWithParams(args.endpoint.fetch, { id: args.id }), 
                    headers: { 'template-edit': 'initial' },
                    success: (response) => {
                        editor.setComponents(response.data.html);
                        editor.setStyle(response.data.css);
                    }
                });
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