import * as rasterizeHTML from 'rasterizehtml';
import merapi from '../../merapi';

window.merapi = window.merapi || {};

window.merapi.renderTemplate = function (url, id) {

    const canvas = $(`#${id}`).get(0);

    rasterizeHTML.drawURL(url, canvas, {
        width: canvas.width,
        height: canvas.height,
        zoom: 1
    });

};