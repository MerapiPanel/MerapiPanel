import Quill from "quill";
import "quill/dist/quill.snow.css";
import $ from "jquery";
import filemanager from "../../FileManager/assets/filemanager";
import Merapi from "../../../base/assets/merapi";
import ImageResize from "quill-image-resize";



Quill.register('modules/imageResize', ImageResize);

var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block', 'formula'],

    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction

    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

    ['link', 'image'],
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],

    ['clean'],                                         // remove formatting button

];


$(document).on("DOMContentLoaded", function () {

    var quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions,
            imageResize: {
                // See optional "config" below
            }
        },
        placeholder: 'Type something...',
        theme: 'snow'
    });

    quill.getModule('toolbar').addHandler('image', function (value) {

        if (value) {
            filemanager.FilePicker().then(value => {
                quill.insertEmbed(quill.getSelection(), 'image', value.path);
            }).catch(err => {
                Merapi.toast("Action cancel", 5, 'text-warning');
            })
        }
    })
})
