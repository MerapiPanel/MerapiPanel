import './app.css';;
import './base/assets/fontawesome/css/all.min.css';
import $ from 'jquery';
import merapi from './merapi';

window.$ = $;
window.merapi = merapi;

let _mod = decodeURIComponent(merapi.getCookie('_module'));
merapi.setCookie('_module', '', -1);

if (_mod) {
    _mod = JSON.parse(_mod);
    for (let i in _mod) {
        require('./Module/' + _mod[i] + "/app.js");
    }
}