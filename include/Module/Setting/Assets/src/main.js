
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
                __.toast(data.message || "Something went wrong", 5, 'text-success');
                submitBtn.html(submitBtnText);
                submitBtn.prop('disabled', false);
            }, 600)
        },
        error: function (data) {
            data = JSON.parse(data.responseText);

            setTimeout(() => {
                __.toast(data.message || "Something went wrong", 5, 'text-danger');
                submitBtn.html(submitBtnText);
                submitBtn.prop('disabled', false);
            }, 600)
        }
    });
})