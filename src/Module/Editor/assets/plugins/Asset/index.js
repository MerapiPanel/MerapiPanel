

const AssetPlugin = (editor, args = {}) => {

    const options = Object.assign({
        url: null,
        name: null,
        upload: null
    }, args);

    
    const am = editor.AssetManager;
    const amConfig = am.getConfig();


    if (options.url) {
        am.clear();
        $.get(options.url, function (e) {
            if (e.data) {
                let assets = e.data.map(function (item) {
                    return {
                        src: item.path,
                        category: item.parent
                    }
                })
                am.add(assets);
            }
        })
    }

    if (options.upload) {
        amConfig.upload = options.upload;
    }
    if (options.name) {
        amConfig.name = options.name;
    }
}

export default AssetPlugin;