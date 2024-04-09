import { toast } from "@il4mb/merapipanel";

$("#form-setting").on('submit', function (e) {

    const submitBtn = $(this).find('[type="submit"]');
    const submitBtnText = submitBtn.text();
    submitBtn.html(`<i class="fa-solid fa-spinner fa-spin-pulse"></i> ${submitBtnText}`);
    submitBtn.prop('disabled', true);

    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        success: function (data) {
            setTimeout(() => {
                toast(data.message, 5, 'text-success');
                submitBtn.html(submitBtnText);
                submitBtn.prop('disabled', false);
            }, 1000)
        },
        error: function (data) {
            setTimeout(() => {
                toast(data.message || data.statusText, 5, 'text-danger');
                submitBtn.html(submitBtnText);
                submitBtn.prop('disabled', false);
            }, 1000)
        }
    });
})