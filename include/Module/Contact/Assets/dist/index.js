/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./include/Module/contact/Assets/src/index.js ***!
  \****************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
var __ = window.__;
var contact = __.contact;
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
contact.events = [];
contact.on = function (key, callback) {
  if (!contact.events[key]) contact.events[key] = [];
  contact.events[key].push(callback);
};
contact.off = function (key, callback) {
  if (!contact.events[key]) return;
  contact.events[key].splice(contact.events[key].indexOf(callback), 1);
};
contact.fire = function (key, data) {
  if (!contact.events[key]) return;
  return new Promise(function (res, rej) {
    var event = new Event(key);
    var promises = contact.events[key].map( /*#__PURE__*/function () {
      var _ref = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(callback) {
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              if (!(typeof callback === 'function' && event.cancelBubble)) {
                _context.next = 3;
                break;
              }
              return _context.abrupt("return");
            case 3:
              return _context.abrupt("return", callback(event, data));
            case 6:
              _context.prev = 6;
              _context.t0 = _context["catch"](0);
              rej(_context.t0);
            case 9:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[0, 6]]);
      }));
      return function (_x) {
        return _ref.apply(this, arguments);
      };
    }());

    // Await all promises
    Promise.all(promises).then(function () {
      return res(event);
    })["catch"](rej);
  });
};
contact.render = function () {
  if (this instanceof HTMLElement) {
    contact.el = this;
  }
  if (!contact.fetchURL) {
    __.toast("No fetch URL found", 5, 'text-danger');
    return;
  }
  __.http.post(contact.fetchURL, {
    'contact-type': contact.filter || null
  }).then(function (response) {
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
  function createComponent(item) {
    return $("<li class=\"list-group-item position-relative\">").append($("<div class=\"d-flex justify-content-start align-items-center\">").append(icons[item.type], $("<div class=\"ms-3\">").append($("<h5 class=\"mb-1\" title=\"".concat(item.is_default ? 'Default contact' : '', "\">")).append(item.name).append(item.is_default ? $("<i class=\"fa-solid fa-star ms-1 text-warning\"></i>") : ''), $("<p class=\"mb-0 text-muted\">").append(item.address).css({
      whiteSpace: "nowrap",
      overflow: "hidden",
      textOverflow: "ellipsis",
      fontSize: "0.8rem"
    })))).append($("<div class=\"dropdown position-absolute top-0 end-0\">").append($("<button class=\"btn btn-sm dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">"), $("<ul class=\"dropdown-menu dropdown-menu-end\">").append($("<li class=\"dropdown-item\" type=\"button\">").append("Edit").on('click', function () {
      return contact.edit(item);
    }), $("<li class=\"dropdown-item text-danger\" type=\"button\">").append("Delete").on('click', function () {
      return contact["delete"](item);
    }), $("<li class=\"dropdown-item\" type=\"button\">").append("Set as default").on('click', function () {
      return contact["default"](item);
    }))));
  }
};
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