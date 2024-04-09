import { toast } from "@il4mb/merapipanel";

$("#form-setting").on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        success: function (data) {
            toast(data.message, 5, 'text-success');
        },
        error: function (data) {
         
            toast(data.message || data.statusText, 5, 'text-danger');
        }
    });
})