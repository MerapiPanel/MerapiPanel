import merapi from '../../../base/assets/merapi';
import $ from 'jquery';

const FileManager = {

}

const createUrl = (path = "") => {
    let prefix = decodeURIComponent(merapi.getCookie("fm-adm-pth"));
    prefix = prefix.replace(/\/$/, "");
    path = path.replace(/^\//, "");
    return `${prefix}/${path}`;
}


const uploadFiles = (files) => {

    const endpoint = createUrl('filemanager/upload');
    const formData = new FormData();

    for (let i = 0; i < files.length; i++) {

        formData.append('files[' + i + ']', files[i]);
    }

    return merapi.post(endpoint, formData);
}


const RenderAssets = (container, data) => {

    container.html('');

    const css_item = {
        flex: '1 1 150px',
        margin: '0px 1rem 1rem 0px',
        with: '100%',
        height: 'auto',
        maxWidth: '150px',
        cursor: 'pointer',
        border: "1px solid #00000011",
        padding: '0.3rem 0.2rem',
        minHeight: '150px',
    };


    return new Promise((resolve) => {

        for (let i in data) {

            if (i == 0) {

                let item = $(`<div class='file-item'>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3V320c0 17.7 14.3 32 32 32s32-14.3 32-32V109.3l73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 53 43 96 96 96H352c53 0 96-43 96-96V352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V352z"/></svg>
                        <small>Upload image</small>
                </div>`);

                item.css(Object.assign({
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-evenly',
                    padding: '0rem 0.5rem',
                    width: '100%',
                    height: '100%'
                }, css_item))
                container.append(item);

                item.on('click', () => {

                    const input = $(`<input type="file" accept="image/*" style="display:none;" />`);
                    input.on('change', () => {
                        uploadFiles(input[0].files).then((res) => {

                            merapi.toast(res.message, 5, 'text-success');
                           resolve(RenderAssets(container, Object.assign({}, data, res.data)))

                        }).catch(function (err) {

                            merapi.toast(err.statusText || err.responseText, 5, 'text-danger');
                        })
                    })
                    input.trigger('click');
                })
            };


            let asset = data[i];
            let item = $(`<div class='file-item'><img src="${asset.path || asset.src}" /><small>${asset.name}</small></div>`);

            item.css(css_item)
            item.find('img').css({ objectFit: 'cover', aspectRatio: 1 })
            container.append(item);

            item.on('click', () => {
                const file = {
                    path: asset.path || asset.src,
                    name: asset.name || 'No name'
                }
                resolve(file);
            })
        }
    })

}




const FilePicker = () => {


    const modal = merapi.createModal('File Manager', null, {
        negative: false,
        positive: false
    });
    modal.show();

    return new Promise((resolve, reject) => {

        merapi.get(createUrl('filemanager/fetchJson')).then((res) => {
            if (res.data) {

                const body = $(modal.container.body);
                const container = $(`<div class="file-container"></div>`);
                container.css({
                    display: "flex",
                    flexDirection: "row",
                    alignItems: "flex-start",
                    justifyContent: "flex-start",
                    alignContent: "stretch",
                    flexWrap: "wrap",
                })

                body.append(container);
                RenderAssets(container, res.data).then((file) => {
                    resolve(file);
                    modal.hide();
                })
            }
        })

        $(document).on('modal:hide', () => {
            reject("Cancel");
        })
    });
}


export default {
    FileManager,
    FilePicker
}