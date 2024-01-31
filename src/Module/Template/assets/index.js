import * as rasterizeHTML from 'rasterizehtml';

const renderTemplate = (url, id) => {

    const canvas = $(`#${id}`).get(0);
    rasterizeHTML.drawURL(url, canvas, {
        width: canvas.width,
        height: canvas.height,
        zoom: 1
    });
}

merapi.assign("template", {
    renderTemplate
})