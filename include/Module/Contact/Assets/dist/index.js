/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./include/Module/Contact/Assets/src/index.js ***!
  \****************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var __ = window.__;
var contact = __.Contact;
var modalContent = $("<form class=\"needs-validation\">").append($("<div class=\"form-group mb-3\">").append($("<label for=\"name\" class=\"form-label w-100\">Name</label>").append($("<input type=\"text\" class=\"form-control\" id=\"name\" placeholder=\"Enter name\" required>")))).append($("<div class=\"form-group mb-3\">").append($("<label for=\"type\" class=\"form-label w-100\">Type</label>").append($("<select class=\"form-select\" id=\"type\">").append($("<option value=\"phone\">Phone</option>"), $("<option value=\"email\">Email</option>"), $("<option value=\"whatsapp\">Whatsapp</option>"))))).append($("<div class=\"form-group mb-3\">").append($("<label for=\"address\" class=\"form-label w-100\" for=\"address\">Phone</label>"), $("<input type=\"text\" class=\"form-control\" id=\"address\" name=\"address\" pattern=\"^[+]?[(]?[0-9]{3}[)]?[-s.]?[0-9]{3}[-s.]?[0-9]{4,12}$\" placeholder=\"Enter phone number\" required>"), $("<div class=\"invalid-feedback\">Invalid phone number</div>")));
var placeholders = {
  phone: 'Enter phone number',
  email: 'Enter email address',
  whatsapp: 'Enter whatsapp number'
};
var labels = {
  phone: 'Phone',
  email: 'Email',
  whatsapp: 'Whatsapp'
};
var pattern = {
  phone: '^[+]?[(]?[0-9]{3}[)]?[-\\s.]?[0-9]{3}[-\\s.]?[0-9]{4,12}$',
  email: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\\.[a-zA-Z]{2,}$',
  whatsapp: '^[+]?[(]?[0-9]{3}[)]?[-\\s.]?[0-9]{3}[-\\s.]?[0-9]{4,12}$'
};
var invalidMessage = {
  phone: 'Invalid phone number',
  email: 'Invalid email address',
  whatsapp: 'Invalid whatsapp number'
};
var icons = {
  phone: "<i class=\"fa-solid fa-phone fa-xl me-2\"></i>",
  email: "<i class=\"fa-solid fa-envelope fa-xl me-2\"></i>",
  whatsapp: "<i class=\"fa-brands fa-whatsapp fa-xl me-2\"></i>"
};
if (!contact.config) contact.config = {};
if (!contact.config.roles) contact.config.roles = {};
var roles = _objectSpread(_objectSpread({}, {
  create: false,
  update: false,
  "delete": false
}), contact.config.roles);
var payload = {
  "contact-type": $("#filter-contact").val() || null
};
$("#filter-contact").on("change", function () {
  payload["contact-type"] = $(this).val();
  contact.render();
});
contact.render = function () {
  if (this instanceof HTMLElement) {
    contact.el = this;
  }
  if (!contact.fetchURL) {
    __.toast("No fetch URL found", 5, 'text-danger');
    return;
  }
  __.http.post(contact.fetchURL, payload).then(function (response) {
    if (!response.data || response.data.length === 0) {
      $(contact.el).html("").append($("<div class=\"d-flex justify-content-center align-items-center\">").css({
        minHeight: "40vh"
      }).append($("<h3 class=\"fs-3 fw-semibold text-muted\">").append("<i class=\"fa-solid fa-quote-left fa-xl me-2\"></i>").append("No contacts found")));
      return;
    }
    $(contact.el).html("").append(response.data.map(createComponent));
  })["catch"](function (error) {
    __.toast(error.message || "Something went wrong", 5, 'text-danger');
  });
};
function createComponent(item) {
  return $("<li class=\"list-group-item position-relative\">").append($("<div class=\"d-flex justify-content-start align-items-center\">").append(icons[item.type], $("<div class=\"ms-3\">").append($("<h5 class=\"mb-1\" title=\"".concat(item.is_default ? 'Default contact' : '', "\">")).append(item.name).append(item.is_default ? $("<i class=\"fa-solid fa-star ms-1 text-warning\"></i>") : ''), $("<p class=\"mb-0 text-muted\">").append(item.address).css({
    whiteSpace: "nowrap",
    overflow: "hidden",
    textOverflow: "ellipsis",
    fontSize: "0.8rem"
  })))).append(roles.update || roles["delete"] ? $("<div class=\"dropdown position-absolute top-0 end-0\">").append($("<button class=\"btn btn-sm dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">"), $("<ul class=\"dropdown-menu dropdown-menu-end\">").append(roles.update ? $("<li class=\"dropdown-item\" type=\"button\">").append("Edit").on('click', function () {
    return contact.edit(item);
  }) : "", roles["delete"] ? $("<li class=\"dropdown-item text-danger\" type=\"button\">").append("Delete").on('click', function () {
    return contact["delete"](item);
  }) : "", roles.update ? $("<li class=\"dropdown-item\" type=\"button\">").append("Set as default").on('click', function () {
    return contact["default"](item);
  }) : "")) : "");
}
contact.add = function () {
  var modal = $("#modal-add-contact").length ? __.modal.from($("#modal-add-contact")) : __.modal.create("Add Contact", modalContent.clone());
  $(modal.el).prop('id', 'modal-add-contact');
  $(modal.el).find('#type').on('change', function () {
    var type = $(this).val();
    var $address = $(modal.el).find('#address');
    $address.parent("label").text(labels[type] || 'Address');
    $address.prop('placeholder', placeholders[type] || 'Enter address');
    $address.prop('pattern', pattern[type] || '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$');
    $address.parent("div").find('.invalid-feedback').text(invalidMessage[type] || 'Invalid address');
    $address.trigger('input');
  });
  $(modal.el).find('#type').trigger('change');
  modal.show();
  modal.action.positive = {
    text: "add contact",
    callback: function callback() {
      var _contact$fire;
      var form = modal.el.find('form').get(0);
      if (!form.checkValidity()) {
        form.reportValidity();
        __.toast("Invalid form", 5, 'text-danger');
        return;
      }
      var type = $(form).find('#type').val();
      var name = $(form).find('#name').val();
      var address = $(form).find('#address').val();
      (_contact$fire = contact.fire('add', {
        name: name,
        address: address,
        type: type
      })) === null || _contact$fire === void 0 || (_contact$fire = _contact$fire.then(function () {
        modal.hide();
        contact.render();
        form.reset();
      })) === null || _contact$fire === void 0 || _contact$fire["catch"](function (error) {
        __.toast(error.message || "Something went wrong", 5, 'text-danger');
      });
    }
  };
};
contact.edit = function (data) {
  var modal = $("#modal-edit-contact").length ? __.modal.from($("#modal-edit-contact")) : __.modal.create("Edit Contact", modalContent.clone());
  $(modal.el).prop('id', 'modal-edit-contact');
  $(modal.el).find('#type').on('change', function () {
    var type = $(this).val();
    var $address = $(modal.el).find('#address');
    $address.parent("label").text(labels[type] || 'Address');
    $address.prop('placeholder', placeholders[type] || 'Enter address');
    $address.prop('pattern', pattern[type] || '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$');
    $address.parent("div").find('.invalid-feedback').text(invalidMessage[type] || 'Invalid address');
    $address.trigger('input');
  });
  $(modal.el).find('#name').val(data.name);
  $(modal.el).find('#type').val(data.type).trigger('change');
  $(modal.el).find('#address').val(data.address);
  modal.show();
  modal.action.positive = {
    text: "update contact",
    callback: function callback() {
      var _contact$fire2;
      var form = modal.el.find('form').get(0);
      if (!form.checkValidity()) {
        form.reportValidity();
        __.toast("Invalid form", 5, 'text-danger');
        return;
      }
      var type = $(form).find('#type').val();
      var name = $(form).find('#name').val();
      var address = $(form).find('#address').val();
      (_contact$fire2 = contact.fire('update', {
        name: name,
        address: address,
        type: type,
        id: data.id
      })) === null || _contact$fire2 === void 0 || (_contact$fire2 = _contact$fire2.then(function () {
        modal.hide();
        contact.render();
        form.reset();
      })) === null || _contact$fire2 === void 0 || _contact$fire2["catch"](function (error) {
        __.toast(error.message || "Something went wrong", 5, 'text-danger');
      });
    }
  };
};
contact["delete"] = function (data) {
  var content = "Are you sure you want to delete <b>".concat(data.name, "</b>?");
  __.dialog.danger("Are you sure ?", content).then(function () {
    var _contact$fire3;
    (_contact$fire3 = contact.fire('delete', data)) === null || _contact$fire3 === void 0 || (_contact$fire3 = _contact$fire3.then(function () {
      contact.render();
    })) === null || _contact$fire3 === void 0 || _contact$fire3["catch"](function (error) {
      __.toast(error.message || "Something went wrong", 5, 'text-danger');
    });
  });
};
contact["default"] = function (data) {
  var _contact$fire4;
  (_contact$fire4 = contact.fire('default', data)) === null || _contact$fire4 === void 0 || (_contact$fire4 = _contact$fire4.then(function () {
    contact.render();
  })) === null || _contact$fire4 === void 0 || _contact$fire4["catch"](function (error) {
    __.toast(error.message || "Something went wrong", 5, 'text-danger');
  });
};
/******/ })()
;