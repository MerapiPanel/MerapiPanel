import * as rasterizeHTML from 'rasterizehtml';
import Merapi from '../../../base/assets/merapi.js';


const Template = {
    renderTemplate: (url, id) => {

        const canvas = $(`#${id}`).get(0);
        rasterizeHTML.drawURL(url, canvas, {
            width: canvas.width,
            height: canvas.height,
            zoom: 1
        });
    }
}


Merapi.assign('Template', Template);