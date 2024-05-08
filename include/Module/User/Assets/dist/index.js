/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************!*\
  !*** ./include/Module/User/Assets/src/index.js ***!
  \*************************************************/
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
var __ = window.__;
var MUser = __.MUser;
var _MUser$opts = MUser.opts,
  endpoints = _MUser$opts.endpoints,
  session = _MUser$opts.session;
MUser.render = function () {
  if (!MUser.container && this instanceof HTMLElement) {
    MUser.container = this;
  }
  __.http.get(endpoints.fetch).then(function (result) {
    $(MUser.container).html("").append(result.data.map(function (user) {
      return createComponent(user);
    }));
  });
};
function createComponent(user) {
  return $("<li class=\"list-group-item d-flex align-items-start position-relative\">").append("<img class=\"w-full h-full object-cover rounded-2\" width=\"45\" height=\"45\" src=\"".concat(user.avatar ? user.avatar : '', "\" alt=\"").concat(user.name, "\">")).append($("<div class=\"ms-2 w-100\">").append("<div class=\"fw-bold\">".concat(user.name, " ").concat(user.email == session.email ? '<small><i>( You )</i></small>' : '', "</div>")).append("<div class='d-flex flex-wrap align-items-end align-content-end'>".concat(user.email, " - <i>").concat(user.role, "</i><span class=\"badge badge-sm bg-").concat(['danger', 'warning', 'primary'][user.status], " ms-2\">").concat(['Inactive', 'Suspended', 'Active'][user.status], "</span></div></div>"))).append($("<div class=\"dropdown position-absolute top-0 end-0\">").append("<button class=\"btn dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton1\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">Actions</button>").append($("<ul class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton1\">").append($("<li><a class=\"dropdown-item text-primary\" href=\"#\">Edit</a></li>").on('click', function () {
    return editUser(user);
  })).append(user.status === 2 ? $("<li><a class=\"dropdown-item  text-warning\" href=\"#\">Suspend</a></li>").on('click', function () {
    return suspendUser(user);
  }) : user.status === 1 ? $("<li><a class=\"dropdown-item  text-success\" href=\"#\">Unsuspend</a></li>").on('click', function () {
    return unsuspendUser(user);
  }) : '').append($("<li><a class=\"dropdown-item bg-danger bg-opacity-10 text-danger\" href=\"#\">Delete</a></li>").on('click', function () {
    return deleteUser(user);
  }))));
}
function editUser(user) {
  var content = $("<form class=\"needs-validation\">").append("<input type=\"hidden\" name=\"id\" value=\"".concat(user.id, "\" readonly>"))
  // name
  .append($("<div class=\"mb-3\">").append("<label for=\"name\" class=\"form-label\">Name</label>").append("<input type=\"text\" class=\"form-control\" name=\"name\" placeholder=\"Enter name\" pattern=\"[a-zA-Z ]+\" id=\"name\" value=\"".concat(user.name, "\" required>")).append("<div class=\"valid-feedback\">Looks good!</div>").append("<div class=\"invalid-feedback\">Please enter a valid name.</div>"))
  // email
  .append($("<div class=\"mb-3\">").append("<label for=\"email\" class=\"form-label\">Email</label>").append("<input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" placeholder=\"Enter email\" pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$\" value=\"".concat(user.email, "\" required>")).append("<div class=\"valid-feedback\">Looks good!</div>").append("<div class=\"invalid-feedback\">Please enter a valid email address.</div>"))
  // role
  .append(user.role && $("<div class=\"mb-3\">").append("<label for=\"role\" class=\"form-label\">Role</label>").append($("<select class=\"form-select\" name=\"role\" id=\"role\" required>").append((MUser.opts.roles || []).map(function (role) {
    return "<option value=\"".concat(role, "\" ").concat(user.role == role ? 'selected' : '', ">").concat(role, "</option>");
  }))))
  // status
  .append(user.status !== undefined && $("<div class=\"mb-3\">").append("<label for=\"status\" class=\"form-label\">Status</label>").append($("<select class=\"form-select\" name=\"status\" id=\"status\" required>").append(['unactive', 'suspended', 'active'].map(function (status, index) {
    return "<option value=\"".concat(index, "\" ").concat(user.status == index ? 'selected' : '', ">").concat(status, "</option>");
  }))))
  // password
  .append($('<div class="mb-3">').append($("<div class='form-check'>").append("<label class=\"form-check-label\" for=\"change-password\">Change Password</label>").append($("<input class=\"form-check-input\" type=\"checkbox\" id=\"change-password\" name=\"change-password\">").on('change', function () {
    if (this.checked) {
      $('#password-container').removeClass('d-none');
      $('#password').prop({
        required: true,
        value: "",
        disabled: false,
        readonly: false
      });
      $('#confirm-password').prop({
        required: true,
        value: "",
        disabled: false,
        readonly: false
      });
    } else {
      $('#password-container').addClass('d-none');
      $('#password').prop({
        required: false,
        value: "",
        disabled: true,
        readonly: true
      });
      $('#confirm-password').prop({
        required: false,
        value: "",
        disabled: true,
        readonly: true
      });
    }
  }))).append($("<div class=\"mb-3 mt-2 d-none\" id=\"password-container\">").append($("<div class=\"mb-3\">").append("<label for=\"password\" class=\"form-label\">Password</label>").append("<input type=\"password\" name=\"password\" placeholder=\"Enter password\" pattern=\"(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{6,}\" class=\"form-control\" id=\"password\" disabled=\"true\">").append("<div class=\"valid-feedback\">Looks good!</div>").append("<div class=\"invalid-feedback\">Password must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters</div>")).append($("<div class=\"mb-3\">").append("<label for=\"confirm-password\" class=\"form-label\">Confirm Password</label>").append($("<input type=\"password\" name=\"confirm-password\" class=\"form-control\" id=\"confirm-password\" disabled=\"true\">").on('input', function () {
    var _this = this;
    setTimeout(function () {
      if ($(_this).val() !== $('#password').val()) {
        $(_this).addClass('is-invalid');
      } else {
        $(_this).removeClass('is-invalid');
      }
    }, 100);
  })).append("<div class=\"valid-feedback d-none\">Looks good!</div>").append("<div class=\"invalid-feedback d-none\">Please enter the same password as the first password</div>"))));
  var modal = $("#modal-edit-user").length > 0 ? __.modal.from($("#modal-edit-user")) : __.modal.create("Edit User", content);
  modal.el.attr("id", "modal-edit-user");
  modal.content = content;
  modal.show();
  modal.action.positive = function () {
    var form = $(modal.el.find('form'));
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      toast('Please enter valid data', 5, 'text-warning');
      return;
    }
    if (form.find('[name="change-password"]').is(':checked')) {
      var password = form.find('[name="password"]').val();
      var confirmPassword = form.find('[name="confirm-password"]').val();
      if (password.length < 6 || password !== confirmPassword) {
        toast('Password and confirm password must be same', 5, 'text-warning');
        form.find('[name="confirm-password"]').addClass('is-invalid');
        form.find('[name="confirm-password"]').trigger('focus');
        return;
      }
    }
    var formData = new FormData(form[0]);
    var dialog_content = $("<div class='w-100 d-block'>");
    dialog_content.append($("<div class='row border-bottom mb-1'>").append("<div class='col-6 fw-semibold'>Old</div>").append("<div class='col-6 fw-semibold'>New</div>"));
    var resolves = {
      status: ['unactive', 'suspended', 'active']
    };
    if (formData.get('change-password')) {
      formData["delete"]('change-password');
    }
    var _iterator = _createForOfIteratorHelper(formData.entries()),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var _step$value = _slicedToArray(_step.value, 2),
          key = _step$value[0],
          value = _step$value[1];
        if (key === 'confirm-password') continue;
        dialog_content.append($("<div class='row'>").append("<div class='col-6 text-muted'>".concat(resolves[key] ? resolves[key][user[key]] : user[key] || key, "</div>")).append("<div class='col-6'>".concat(!key.includes("password") ? resolves[key] ? resolves[key][value] : value : value.split('').map(function () {
          return '*';
        }).join(''), "</div>")));
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    __.dialog.confirm("Confirm Change ?", dialog_content).then(function () {
      __.http.post(endpoints.update, formData).then(function (result) {
        if (result.code === 200) {
          __.toast('User updated successfully', 5, 'text-success');
          MUser.render();
        } else {
          throw new Error(result.message);
        }
      })["catch"](function (error) {
        __.toast(error.message || error.statusText || error, 5, 'text-danger');
      });
    })["catch"](function () {
      __.toast('Action Canceled', 5, 'text-warning');
    });
  };
}
function deleteUser(user) {
  __.dialog.danger("Are you sure?", $("<div>Delete <b>".concat(user.name, "</b>?</div>")).append("<p>Are you sure you want to delete user details as shown below?</p>").append($("<table class=\"table table-bordered\">").append($("<tr>").append("<th>id</th>").append("<td>".concat(user.id, "</td>"))).append($("<tr>").append("<th>Email</th>").append("<td>".concat(user.email, "</td>"))).append($("<tr>").append("<th>Role</th>").append("<td>".concat(user.role, "</td>")))).append("<p class=\"text-danger\"><i class=\"fa-solid fa-triangle-exclamation\"></i> This action cannot be undone.</p>")).then(function () {
    __.http.post(endpoints["delete"], {
      id: user.id
    }).then(function (result) {
      if (result.code === 200) {
        __.toast('User deleted successfully', 5, 'text-success');
        MUser.render();
      } else {
        throw new Error(result.message);
      }
    })["catch"](function (error) {
      __.toast(error.message || error.statusText || error, 5, 'text-danger');
    });
  })["catch"](function () {
    __.toast('Action Canceled', 5, 'text-warning');
  });
}
function suspendUser(user) {
  __.dialog.warning("Are you sure?", $("<div>Suspend <b>".concat(user.name, "</b>?</div>")).append("<p>Are you sure you want to suspend user details as shown below?</p>").append($("<table class=\"table table-bordered\">").append($("<tr>").append("<th>id</th>").append("<td>".concat(user.id, "</td>"))).append($("<tr>").append("<th>Email</th>").append("<td>".concat(user.email, "</td>"))).append($("<tr>").append("<th>Role</th>").append("<td>".concat(user.role, "</td>"))))).then(function () {
    __.http.post(endpoints.update, {
      status: 1,
      id: user.id
    }).then(function (result) {
      if (result.code === 200) {
        __.toast('User suspended successfully', 5, 'text-success');
        MUser.render();
      } else {
        throw new Error(result.message);
      }
    })["catch"](function (error) {
      __.toast(error.message || error.statusText || error, 5, 'text-danger');
    });
  })["catch"](function () {
    __.toast('Action Canceled', 5, 'text-warning');
  });
}
function unsuspendUser(user) {
  __.dialog.confirm("Are you sure?", $("<div>Unsuspend <b>".concat(user.name, "</b>?</div>")).append("<p>Are you sure you want to unsuspend user details as shown below?</p>").append($("<table class=\"table table-bordered\">").append($("<tr>").append("<th>id</th>").append("<td>".concat(user.id, "</td>"))).append($("<tr>").append("<th>Email</th>").append("<td>".concat(user.email, "</td>"))).append($("<tr>").append("<th>Role</th>").append("<td>".concat(user.role, "</td>"))))).then(function () {
    __.http.post(endpoints.update, {
      status: 2,
      id: user.id
    }).then(function (result) {
      if (result.code === 200) {
        __.toast('User unsuspended successfully', 5, 'text-success');
        MUser.render();
      } else {
        throw new Error(result.message);
      }
    })["catch"](function (error) {
      __.toast(error.message || error.statusText || error, 5, 'text-danger');
    });
  })["catch"](function () {
    __.toast('Action Canceled', 5, 'text-warning');
  });
}
/******/ })()
;