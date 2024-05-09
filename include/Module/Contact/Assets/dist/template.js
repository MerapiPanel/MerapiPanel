/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./include/Module/Contact/Assets/src/template.js":
/*!*******************************************************!*\
  !*** ./include/Module/Contact/Assets/src/template.js ***!
  \*******************************************************/
/***/ (function() {

var _this = this;
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var __ = window.__;
var template = __.Contact.Template;
var icons = {
  "default": "<i class=\"fa-solid fa-user me-2\"></i>",
  phone: "<i class=\"fa-solid fa-phone me-2\"></i>",
  email: "<i class=\"fa-solid fa-envelope me-2\"></i>",
  whatsapp: "<i class=\"fa-brands fa-whatsapp me-2\"></i>"
};
var payload = {
  type: $("#filter-contact").val() || null
};
var roles = _objectSpread({
  modifyTemplate: false
}, __.Contact.config.roles);
$('#filter-contact').on('change', function () {
  payload.type = $(this).val();
  template.render();
});
template.render = function () {
  if (_this instanceof HTMLElement) {
    template.el = _this;
  }
  if (!template.fetchURL) {
    __.toast("No fetch URL found", 5, 'text-danger');
    return;
  }
  __.http.post(template.fetchURL, payload).then(function (response) {
    if (!response.data || response.data.length === 0) {
      $(template.el).html("").append($("<div class=\"d-flex justify-content-center align-items-center\">").css({
        minHeight: "40vh"
      }).append($("<h3 class=\"fs-3 fw-semibold text-muted\">").append("<i class=\"fa-solid fa-quote-left fa-xl me-2\"></i>").append("No templates found")));
      return;
    }
    $(template.el).html("").append(response.data.map(createComponent));
  })["catch"](function (error) {
    __.toast(error.message || "Something went wrong", 5, 'text-danger');
  });
};
function createComponent(item) {
  return $("<li class=\"list-group-item position-relative d-flex justify-content-start align-items-center \">").append($("<div>").append('<i class="fa-regular fa-file-lines fa-2x text-muted me-2"></i>'), $("<div class=\"ms-1\">").append("<h4 class=\"mb-1\">".concat(item.name, "</h4>")).append($("<div class=\"d-flex justify-content-start align-items-center\">").append(icons[item.contact.type || 'default']).append("<small>".concat(item.contact.name || '<i>No name</i>', "</small>")))).append(roles.modifyTemplate == true ? $("<div class=\"dropdown position-absolute top-0 end-0\">").append($("<button class=\"btn btn-sm dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">"), $("<ul class=\"dropdown-menu dropdown-menu-end\">").append($("<li class=\"dropdown-item\" type=\"button\">").append("Edit").on('click', function () {
    return template.edit(item);
  })).append($("<li class=\"dropdown-item text-danger\" type=\"button\">").append("Delete").on('click', function () {
    return template["delete"](item);
  }))) : "");
}
template.edit = function (item) {
  var _template$fire;
  (_template$fire = template.fire('edit', item)) === null || _template$fire === void 0 || _template$fire.then(function () {
    template.render();
  });
};
template["delete"] = function (item) {
  __.dialog.confirm("Are you sure?", "Are you sure you want to delete <b>".concat(item.name, "</b>?")).then(function () {
    var _template$fire2;
    (_template$fire2 = template.fire('delete', item)) === null || _template$fire2 === void 0 || _template$fire2.then(function () {
      __.toast("Template deleted successfully", 5, 'text-success');
      template.render();
    })["catch"](function (error) {
      __.toast(error.message || "Something went wrong", 5, 'text-danger');
    });
  });
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./include/Module/Contact/Assets/src/template.js"]();
/******/ 	
/******/ })()
;