/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./include/Module/website/Assets/src/routes.ts ***!
  \*****************************************************/
__webpack_require__.r(__webpack_exports__);
var __ = window.__;
__.routes = {
  templateFetchURL: null,
  render: function render(container, endpoint) {
    function createComponent(route) {
      return $("<li class=\"mb-3\">").append($("<div class=\"d-flex align-items-end flex-wrap".concat(route.id === null ? ' text-primary' : '', "\">")).append($("<div>").append("<b>".concat(route.title, "</b>")).append($("<small class=\"text-primary d-block\" style=\"cursor:pointer\">".concat(toFullURL(route.route), "</small>")).on('click', function () {
        window.open(toFullURL(route.route), '_blank');
      }))).append(route.id === null ? "" : $("<div class=\"ms-auto btn-group\" role=\"group\" aria-label=\"Basic example\">").append(route.template_id === null ? "" : $("<button type=\"button\" class=\"btn btn-sm px-4 btn-outline-primary\">Edit Template</button>").on('click', function () {
        __.routes.fire('editTemplate', route);
      }), $("<button type=\"button\" class=\"btn btn-sm px-4 btn-outline-primary\">Edit</button>").on('click', function () {
        if (__.routes.fire("pre:edit", route).cancelBubble) return;
        __.routes.edit(route);
      }), $("<button type=\"button\" class=\"btn btn-sm px-4 btn-outline-danger\">Delete</button>").on('click', function () {
        __.routes["delete"](route);
      }))));
    }
    function toFullURL(url) {
      return window.location.protocol + "//" + window.location.host + (url.charCodeAt(0) === 47 ? url : "/" + url);
    }
    __.http.get(endpoint, {}).then(function (response) {
      $(container).html("").append(response.data.map(createComponent));
    });
  },
  add: function add() {
    var content = $("<form class='needs-validation'>").append($("<div class=\"form-group mb-3\">").append("<label for=\"title\">Title</label>").append("<input type=\"text\" class=\"form-control\" id=\"title\" placeholder=\"Enter title\" required/>").append("<div class=\"invalid-feedback\">Please provide a valid title.</div>")).append($("<div class=\"form-group mb-3\">").append("<label for=\"template\">Template</label>").append($("<select type=\"text\" class=\"form-select\" id=\"template\"></select>").append($("<option value=\"\">-- Select Template --</option>")))).append($("<div class=\"form-group mb-3\">").append("<label for=\"route\">Route</label>").append("<input type=\"text\" class=\"form-control\" id=\"route\" placeholder=\"Enter route\" required/>").append("<div class=\"invalid-feedback\">Please provide a valid route.</div>"));
    var modal = $("#modal-add-route").length <= 0 ? __.modal.create("Add Route", content) : __.modal.from($("#modal-add-route"));
    modal.el.prop('id', 'modal-add-route');
    modal.show();
    $(modal.el).find('#title').on('input', function () {
      var _$$val;
      var route = $(modal.el).find('#route');
      route.val("/" + ((_$$val = $(this).val()) === null || _$$val === void 0 || (_$$val = _$$val.replace(/[^a-zA-Z0-9_]+/g, '-')) === null || _$$val === void 0 || (_$$val = _$$val.toLowerCase()) === null || _$$val === void 0 || (_$$val = _$$val.replace(/-$/g, '')) === null || _$$val === void 0 ? void 0 : _$$val.replace(/^-/, '')));
    });
    var timeout;
    $(modal.el).find('#route').on('input', function () {
      var _$$val2,
        _this = this;
      var value = "/" + ((_$$val2 = $(this).val()) === null || _$$val2 === void 0 || (_$$val2 = _$$val2.replace(/[^a-zA-Z0-9_]+/g, '-')) === null || _$$val2 === void 0 || (_$$val2 = _$$val2.toLowerCase()) === null || _$$val2 === void 0 || (_$$val2 = _$$val2.replace(/^-/, '')) === null || _$$val2 === void 0 ? void 0 : _$$val2.replace(/\\|\/$/g, ''));
      $(this).val(value);
      if (timeout) clearTimeout(timeout);
      timeout = setTimeout(function () {
        var _$$val3;
        $(_this).val((_$$val3 = $(_this).val()) === null || _$$val3 === void 0 ? void 0 : _$$val3.replace(/-$/g, ''));
      }, 1000);
    });
    $(modal.el).find('#template').on('click', function (e) {
      e.preventDefault();
      e.currentTarget.blur();
      selectTemplates().then(function (template) {
        modal.el.find('#template').html('').append("<option value=\"".concat(template.id, "\" selected>").concat(template.title, "</option>"));
      });
    });
    return new Promise(function (resolve, reject) {
      modal.on("modal:hide", function () {
        reject();
      });
      modal.action.positive = function () {
        var title = modal.el.find('#title').val();
        var route = modal.el.find('#route').val();
        var template = modal.el.find('#template').val();
        if (!modal.el.find('form').get(0).checkValidity()) {
          __.toast('Form is not valid', 5, 'text-danger');
          return;
        }
        if (!title || (title || "").length <= 0) {
          __.toast('Title is required', 5, 'text-danger');
          return;
        }
        if (!route || (route || "").length <= 0) {
          __.toast('Route is required', 5, 'text-danger');
          return;
        }
        resolve({
          title: title,
          route: route,
          template: template
        });
        modal.hide();
        modal.el.find('form').get(0).reset();
      };
    });
  },
  edit: function edit(route) {
    var content = $("<form class=\"needs-validation\">").append($("<div class=\"form-group mb-3\">").append("<label for=\"id\">id</label>").append("<input type=\"text\" class=\"form-control\" id=\"id\" placeholder=\"Enter id\" value=\"".concat(route.id, "\" readonly/>"))).append($("<div class=\"form-group mb-3\">").append("<label for=\"title\">Title</label>").append("<input type=\"text\" class=\"form-control\" id=\"title\" placeholder=\"Enter title\" value=\"".concat(route.title, "\" required/>")).append("<div class=\"invalid-feedback\">Please provide a valid title.</div>")).append($("<div class=\"form-group mb-3\">").append("<label for=\"template\">Template</label>").append($("<select type=\"text\" class=\"form-select\" id=\"template\"></select>").append($("<option value=\"".concat(route.template_id, "\">").concat(route.template_title, "</option>"))))).append($("<div class=\"form-group mb-3\">").append("<label for=\"route\">Route</label>").append("<input type=\"text\" class=\"form-control\" id=\"route\" placeholder=\"Enter route\" value=\"".concat(route.route, "\" ").concat(route.route == "/" ? "disabled" : "", " required/>")).append("<div class=\"invalid-feedback\">Please provide a valid route.</div>"));
    var modal = $("#modal-edit-route").length <= 0 ? __.modal.create("Edit Route", content) : __.modal.from($("#modal-edit-route"));
    modal.el.prop('id', 'modal-edit-route');
    modal.show();
    modal.el.find('#id').val(route.id);
    modal.el.find('#title').val(route.title);
    modal.el.find('#route').val(route.route);
    $(modal.el).find('#title').on('input', function () {
      var _$$val4;
      var route = $(modal.el).find('#route');
      route.val("/" + ((_$$val4 = $(this).val()) === null || _$$val4 === void 0 || (_$$val4 = _$$val4.replace(/[^a-zA-Z0-9_]+/g, '-')) === null || _$$val4 === void 0 || (_$$val4 = _$$val4.toLowerCase()) === null || _$$val4 === void 0 || (_$$val4 = _$$val4.replace(/-$/g, '')) === null || _$$val4 === void 0 ? void 0 : _$$val4.replace(/^-/, '')));
    });
    var timeout;
    $(modal.el).find('#route').on('input', function () {
      var _$$val5,
        _this2 = this;
      var value = "/" + ((_$$val5 = $(this).val()) === null || _$$val5 === void 0 || (_$$val5 = _$$val5.replace(/[^a-zA-Z0-9_]+/g, '-')) === null || _$$val5 === void 0 || (_$$val5 = _$$val5.toLowerCase()) === null || _$$val5 === void 0 || (_$$val5 = _$$val5.replace(/^-/, '')) === null || _$$val5 === void 0 ? void 0 : _$$val5.replace(/\\|\/$/g, ''));
      $(this).val(value);
      if (timeout) clearTimeout(timeout);
      timeout = setTimeout(function () {
        var _$$val6;
        $(_this2).val((_$$val6 = $(_this2).val()) === null || _$$val6 === void 0 ? void 0 : _$$val6.replace(/-$/g, ''));
      }, 1000);
    });
    $(modal.el).find('#template').on('click', function (e) {
      e.preventDefault();
      e.currentTarget.blur();
      selectTemplates(route.template).then(function (template) {
        modal.el.find('#template').html('').append("<option value=\"".concat(template.id, "\" selected>").concat(template.title, "</option>"));
      });
    });
    modal.on("modal:hide", function () {
      modal.el.find('input').each(function () {
        $(this).val('').removeClass('is-valid is-invalid');
      });
    });
    modal.action.positive = function () {
      var id = modal.el.find('#id').val();
      var title = modal.el.find('#title').val();
      var route = modal.el.find('#route').val();
      var template = modal.el.find('#template').val();
      if (!modal.el.find('form').get(0).checkValidity()) {
        __.toast('Provide a valid form', 5, 'text-danger');
        return;
      }
      if (!id || (id || "").length <= 0) {
        __.toast('id is required', 5, 'text-danger');
        return;
      }
      if (!title || (title || "").length <= 0) {
        __.toast('Title is required', 5, 'text-danger');
        return;
      }
      if (!route || (route || "").length <= 0) {
        __.toast('Route is required', 5, 'text-danger');
        return;
      }
      __.routes.fire('edit', {
        id: id,
        title: title,
        route: route,
        template: template
      });
      modal.hide();
      modal.el.find('form').get(0).reset();
    };
  },
  "delete": function _delete(route) {
    __.dialog.danger("Are you sure?", "Are you sure to delete this route <b>" + route.title + '</b> with id <i>' + route.id + '</i>').then(function () {
      __.routes.fire('delete', route);
    })["catch"](function () {
      __.toast('Cancelled', 5, 'text-warning');
    });
  },
  callbacks: {},
  on: function on(event, callback) {
    if (!__.routes.callbacks[event]) {
      __.routes.callbacks[event] = [];
    }
    __.routes.callbacks[event].push(callback);
  },
  fire: function fire(event) {
    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }
    var _event = new Event(event);
    if (__.routes.callbacks[event]) {
      __.routes.callbacks[event].forEach(function (callback) {
        if (_event.cancelBubble) return;
        callback.apply(void 0, [_event].concat(args));
      });
    }
    return _event;
  }
};
function selectTemplates() {
  var template = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  var content = $("<div id=\"content\" class=\"row row-cols-1 row-cols-md-2 g-4\"></div>");
  var modal = $("#modal-select-template").length <= 0 ? __.modal.create("Select Template", content) : __.modal.from($("#modal-select-template"));
  modal.el.prop('id', 'modal-select-template');
  modal.show();
  modal.el.find("#content").append($("<div id='loading-container'>").css({
    height: '150px',
    width: '100%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    position: 'absolute',
    top: 0,
    left: 0,
    zIndex: 5
  }).append("<i class=\"fa-solid fa-spinner fa-spin fa-2x\"></i>"));
  function createTemplate(data) {
    return $("<label class=\"col d-flex align-items-center\" style=\"max-width: 14rem; gap: 1rem;\">").append($("<input type=\"radio\" name=\"template\" value=\"".concat(data.id, "\" ").concat(template == data.id ? 'checked' : '', ">")), $("<div class=\"card card-body\">").append($("<h5 class=\"card-title\">".concat(data.title, "</h5>"))).append($("<small class=\"card-text\">".concat(data.description || '<span class="text-muted">No description</span>', "</small>"))));
  }
  var templates = [];
  __.http.get(__.routes.templateFetchURL, {}).then(function (response) {
    templates = response.data;
    modal.el.find("#content").html("").append(templates.map(createTemplate));
  });
  return new Promise(function (resolve, reject) {
    modal.action.positive = {
      text: "Select",
      callback: function callback() {
        var id = modal.el.find('input[name="template"]:checked').val();
        if (!id) {
          __.toast('Please select a template', 5, 'text-danger');
          return;
        }
        var template = templates.find(function (x) {
          return x.id == id;
        });
        resolve(template);
        modal.hide();
      }
    };
  });
}

/******/ })()
;