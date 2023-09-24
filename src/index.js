require('./app.css')
require('./base/assets/fontawesome/css/all.min.css');
const $ = require('jquery');
import merapi from './merapi';

window.$ = $;
window.merapi = merapi;

let _mod = decodeURIComponent(merapi.getCookie('_module'));
merapi.setCookie('_module', '', -1);

if (_mod) {
    _mod = JSON.parse(_mod);
    for (let i in _mod) {
        require('./Module/' + _mod[i] + "/Assets/app.js");
    }
}