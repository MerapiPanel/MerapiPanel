/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************!*\
  !*** ./include/Module/Auth/Assets/src/login.js ***!
  \*************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var __ = window.__;
var MAuth = __.Auth;
MAuth.payload = {
  latitude: null,
  longitude: null,
  email: null
};
var delay;
function submitHandler(e) {
  if (delay) clearTimeout(delay);
  e.preventDefault();
  if (!this.checkValidity()) {
    return;
  }
  $("#loading").removeClass("d-none");
  $("#login-btn").prop("disabled", true);
  $("#error").addClass("d-none");
  var form = $(this);
  var url = form.attr("action");
  var email = form.find("[name='email']").val();
  var password = form.find("[name='password']").val();
  if (!email || email.length == 0) {
    __.toast("Email is required", 5, 'text-danger');
    $("#loading").addClass("d-none");
    $("#login-btn").prop("disabled", false);
    $("#error").removeClass("d-none");
    $("#error").text("Email is required");
    return;
  }
  if (!password || password.length == 0) {
    __.toast("Password is required", 5, 'text-danger');
    $("#loading").addClass("d-none");
    $("#login-btn").prop("disabled", false);
    $("#error").removeClass("d-none");
    $("#error").text("Password is required");
    return;
  }
  delay = setTimeout(function () {
    __.http.post(url, _objectSpread(_objectSpread({}, MAuth.payload), {
      email: email,
      password: password
    })).then(function (response) {
      window.location.reload();
    })["catch"](function (error) {
      __.toast(error.message || "Error: Please try again!", 5, 'text-danger');
      $("#loading").addClass("d-none");
      $("#login-btn").prop("disabled", false);
      $("#error").removeClass("d-none");
      $("#error").text(error.message || "Error: Please try again!");
    });
    $("#login-form").off("submit", submitHandler).on("submit", submitHandler);
  }, 1000);
}
if (MAuth.config.geo) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (location) {
      MAuth.payload.latitude = location.coords.latitude;
      MAuth.payload.longitude = location.coords.longitude;
      $("#login-form").on("submit", submitHandler);
    }, function (error) {
      __.toast(error.message || "Error getting location", 5, "text-danger");
      $("#login-form").off("submit", submitHandler);
      $("[type='submit']").prop("disabled", true);
      $("input").prop("disabled", true);
    });
  } else {
    __.toast("Geolocation is not supported by this browser.", 5, "text-danger");
    $("#login-form").off("submit", submitHandler);
    $("[type='submit']").prop("disabled", true);
    $("input").prop("disabled", true);
  }
} else {
  $("#login-form").on("submit", submitHandler);
}
$("#login-form").on("submit", submitHandler);
/******/ })()
;