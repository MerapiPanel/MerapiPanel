/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./include/Module/Article/Assets/src/editor.js ***!
  \*****************************************************/
var _Article$data, _Article$data2, _Article$data3, _Article$data4, _Article$data5, _Article$data6, _Article$data7;
// import { __MP } from "../../../../Buildin/src/main";

var __ = window.__;
var Article = __.Article;
if (!Article.data) {
  Article.data = {};
}
var form = $("<form class=\"needs-validation\">").append(((_Article$data = Article.data) === null || _Article$data === void 0 ? void 0 : _Article$data.id) && $("<div class=\"mb-3 py-2\">ID : ".concat(Article.data.id, "</div>"))).append($("<div class=\"mb-3\">").append($("<label class=\"d-block\">").append("Enter title:").append("<input class=\"form-control\" type=\"text\" name=\"title\" placeholder=\"Enter title\" pattern=\"[a-zA-Z\\s]{5,}\" invalid-message=\"Please enter title\" value=\"".concat(((_Article$data2 = Article.data) === null || _Article$data2 === void 0 ? void 0 : _Article$data2.title) || "", "\">"))).append("<div class=\"invalid-feedback\">Please enter title with at least 5 characters a-z</div>")).append($("<div class='mb-3'>").append($("<label class='d-block'>").append("Enter slug:").append("<input class=\"form-control\" type=\"text\" name=\"slug\" placeholder=\"Enter slug\" value=\"".concat(((_Article$data3 = Article.data) === null || _Article$data3 === void 0 ? void 0 : _Article$data3.slug) || "", "\">")))).append($("<div class='mb-3'>").append($("<label class=\"d-block\">").append("Enter keywords:").append("<input class=\"form-control\" type=\"text\" name=\"keywords\" placeholder=\"Enter keywords\" value=\"".concat(((_Article$data4 = Article.data) === null || _Article$data4 === void 0 ? void 0 : _Article$data4.keywords) || "", "\">")))).append($("<div class='mb-3'>").append($("<label class='d-block'>").append("Enter description:").append("<textarea class=\"form-control\" name=\"description\" placeholder=\"Enter description\">".concat(((_Article$data5 = Article.data) === null || _Article$data5 === void 0 ? void 0 : _Article$data5.description) || "", "</textarea>")))).append($("<div class='mb-3'>").append($("<label class='d-block'>").append("Enter category:").append("<input class=\"form-control\" type=\"text\" name=\"category\" placeholder=\"Enter category\" value=\"".concat(((_Article$data6 = Article.data) === null || _Article$data6 === void 0 ? void 0 : _Article$data6.category) || '', "\">")))).append($("<div class='mb-3'>").append($("<label class='d-block form-check'>").append("<input class='form-check-input' type=\"checkbox\" name=\"status\" ".concat((_Article$data7 = Article.data) !== null && _Article$data7 !== void 0 && _Article$data7.status ? 'checked' : '', ">")).append("<span class='form-check-label'>Publish</span>"))).append($('<div class="mb-3 d-flex">').append('<button type="submit" class="ms-auto btn btn-primary">Submit</button>'));
$(form).find('[name="title"]').on('input', function () {
  $(form).find('[name="slug"]').val($(this).val().split(/\s+/).filter(function (w) {
    return w.length > 0;
  }).join('-').toLowerCase());
});
editor.callback = function (data) {
  var _this = this;
  var editor = this.editor;
  var modal = editor.Modal;
  var isComplete = false;
  if (!Article.endpoints.save) {
    __.toast("Please set save endpoint", 5, "text-danger");
    return;
  }
  modal.open({
    title: 'Article Meta Data',
    attributes: {
      "class": 'my-class'
    }
  });
  modal.setContent($("<div class=\"form-article\">").append(form));
  modal.onceClose(function () {
    if (!isComplete) _this.reject("Action canceled");
  });
  form.off('submit'); // prevent double submit

  form.on('submit', function (e) {
    e.preventDefault();
    console.clear();
    if (!form[0].checkValidity()) {
      __.toast("Please enter valid data", 5, "text-danger");
      return;
    }
    isComplete = true;
    editor.Modal.close();
    var title = form.find('[name="title"]').val();
    var slug = form.find('[name="slug"]').val();
    var keywords = form.find('[name="keywords"]').val();
    var category = form.find('[name="category"]').val();
    var description = form.find('[name="description"]').val();
    var status = form.find('[name="status"]').is(':checked') ? 1 : 0;
    __.http.post(Article.endpoints.save, Object.assign(Article.data.id ? {
      id: Article.data.id
    } : {}, {
      title: title,
      slug: slug,
      keywords: keywords,
      category: category,
      description: description,
      data: JSON.stringify(_this.data),
      status: status
    })).then(function (response) {
      Article.data.id = response.data.id;
      if (window.history.replaceState && Article.endpoints.editURL) {
        var target = Article.endpoints.editURL.replace("{id}", response.data.id);
        window.history.replaceState(null, null, target);
      }
      _this.resolve(response);
    })["catch"](function (err) {
      return _this.reject(err);
    });
  });
};
/******/ })()
;