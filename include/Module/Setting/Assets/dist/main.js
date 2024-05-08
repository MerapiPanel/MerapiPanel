/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************!*\
  !*** ./include/Module/Setting/Assets/src/main.js ***!
  \***************************************************/
$("#form-setting").on('submit', function (e) {
  var submitBtn = $(this).find('[type="submit"]');
  var submitBtnText = submitBtn.text();
  submitBtn.html("<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i> ".concat(submitBtnText));
  submitBtn.prop('disabled', true);
  e.preventDefault();
  $.ajax({
    url: $(this).attr('action'),
    type: $(this).attr('method'),
    data: $(this).serialize(),
    success: function success(data) {
      setTimeout(function () {
        __.toast(data.message || "Something went wrong", 5, 'text-success');
        submitBtn.html(submitBtnText);
        submitBtn.prop('disabled', false);
      }, 600);
    },
    error: function error(data) {
      data = JSON.parse(data.responseText);
      setTimeout(function () {
        __.toast(data.message || "Something went wrong", 5, 'text-danger');
        submitBtn.html(submitBtnText);
        submitBtn.prop('disabled', false);
      }, 600);
    }
  });
});
/******/ })()
;