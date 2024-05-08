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
/*!****************************************************!*\
  !*** ./include/Module/Product/Assets/src/index.ts ***!
  \****************************************************/
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var __ = window.__;
var MProduct = __.MProduct;
console.log(MProduct);
MProduct.render = function () {
  if (this instanceof HTMLElement) {
    MProduct.el = this;
  }
  __.http.get(MProduct.endpoints.fetchAll, {}).then(function (response) {
    setTimeout(function () {
      $(MProduct.el).html("").append(response.data.map(createCard));
      if (!response.data || !response.data.length) {
        $(MProduct.el).html("").css({
          position: 'relative',
          flex: '1'
        }).append($("<div>No data found</div>").css({
          position: "absolute",
          top: "50%",
          left: "50%",
          transform: "translate(-50%, -50%)",
          textAlign: "center"
        }));
      }
    }, 400);
  })["catch"](function (error) {
    __.toast(error instanceof String ? error : error.message || "Error on rendering data", 5, 'text-danger');
  });
};
function createCard(product) {
  var config = _objectSpread({
    currency: "USD",
    currency_symbol: "$"
  }, MProduct.config || {});
  var content = $("<div class='card position-relative w-100' style='max-width: 18rem;'>").append(product.images ? $("<div id='carousel-".concat(product.id, "' class=\"carousel slide carousel-fade\" data-bs-ride=\"carousel\" onload=\"this.init()\">")).append($("<div class='carousel-inner'>").append(product.images.map(function (image, i) {
    return $("<div class='carousel-item".concat(i === 0 ? ' active' : '', "' data-bs-interval=\"").concat(Math.random() * (6000 - 3000) + 3000, "\">")).append($("<img class='d-block w-100' src='".concat(image, "' style='height: 200px;object-fit: cover;'>")));
  }))) : $("<div class='carousel-item'>").append($("<div>No images</div>"))).append($("<div class='card-body'>").append($("<h5 class='card-title'>".concat(product.title, "</h5>"))).append($("<p class='card-text'>".concat((product.description || "").length > 100 ? (product.description || "").slice(0, 75) + "..." : product.description, "</p>"))).append($("<div><span class='fw-semibold'>".concat(config.currency_symbol, " ").concat(product.price, "</span> | <i class='fw-light'>").concat(product.category, "</i></div>")))).append($("<div class='dropdown position-absolute end-0 top-0' style='z-index: 1;' role='dropdown'>").append("<button class='btn dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button>").append($("<div class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>").append($("<a class='dropdown-item' href='#'>View</a>").on('click', function () {
    return actionView(product);
  })).append($("<a class='dropdown-item' href='#'>Quick Edit</a>").on('click', function () {
    return actionQuickEdit(product);
  })).append($("<a class='dropdown-item' href='#'>Edit</a>").on('click', function () {
    return actionEdit(product);
  })).append($("<a class='dropdown-item' href='#'>Delete</a>").on('click', function () {
    return actionDelete(product);
  }))));
  content.find('.carousel')[0].init = function () {
    if (window.bootstrap) {
      var carousel = new window.bootstrap.Carousel(this);
      $(this).on('destroyed', function () {
        return carousel.dispose();
      });
    }
  };
  return content;
}
function actionView(product) {
  window.location.href = __.endpoints.view.replace("{id}", product.id);
}
function actionQuickEdit(product) {
  var content = $("<form class='row g-3 needs-validation'>").append($("<div class='form-group'>").append($("<label class='form-label'>Title</label>")).append("<input type='text' class='form-control' name='title' placeholder='Enter title' value='".concat(product.title, "' pattern='[a-zA-Z0-9\\s]{3,255}' required>")).append($("<div class='invalid-feedback'>Please provide a valid title, at least 3 characters.</div>")).append($("<div class='valid-feedback'>Looks good!</div>")), $("<div class='form-group'>").append($("<label class='form-label'>Price</label>")).append($("<input type='text' class='form-control' name='price' placeholder='Enter price' inputmode='numeric' pattern='[0-9.]*' maxlength='24' value='".concat(product.price, "'>")).on('input', function () {
    $(this).val(($(this).val() || "").replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
  })).append($("<div class='invalid-feedback'>Please enter number only.</div>")).append($("<div class='valid-feedback'>Looks good!</div>")), $("<div class='form-group'>").append($("<label class='form-label'>Category</label>")).append("<input type='text' class='form-control' name='category' placeholder='Enter category' pattern='[a-zA-Z]{3,24}[a-zA-Z0-9\\s]{0,24}' required value='".concat(product.category, "'>")).append("<div class='invalid-feedback'>Please provide a valid category, at least 3 characters.</div>").append("<div class='valid-feedback'>Looks good!</div>"), $("<div class='form-group'>").append($("<label class='form-label'>Description</label>")).append($("<textarea class='form-control' name='description' placeholder='Enter description' rows='3' minlength='30' maxlength='255' required>".concat(product.description, "</textarea>")).on('input', function () {
    $(this).parent(".form-group").find(".length").text($(this).val().length + "/255");
  })).append("<small class='length'>".concat(product.description.length, "/255</small>")).append("<div class='valid-feedback'>Looks good!</div>"), $("<div class='form-group'>").append($("<label class='form-label'>Status</label>")).append($("<select class='form-select' name='status'>").append($("<option value='1' ".concat(product.status === 1 ? 'selected' : '', ">Publish</option>")), $("<option value='0' ".concat(product.status === 0 ? 'selected' : '', ">Unpublish</option>")))));
  var modal = $("#modal-quick-edit").length ? __.modal.from($("#modal-quick-edit")) : __.modal.create("Quick Edit", null);
  modal.content = content;
  modal.show();
  modal.action.positive = function () {
    if (content[0].checkValidity()) {
      __.http.post(MProduct.endpoints.update, {
        id: product.id,
        title: content.find("input[name='title']").val(),
        price: content.find("input[name='price']").val(),
        category: content.find("input[name='category']").val(),
        description: content.find("textarea[name='description']").val(),
        status: content.find("select[name='status']").val()
      }).then(function (res) {
        if (res.code === 200) {
          __.toast("Product updated", 5, "text-success");
          MProduct.render();
        } else throw new Error(res.message);
      })["catch"](function (err) {
        __.toast(err.message || "Failed to update product", 5, "text-danger");
      });
    } else {
      __.toast("Please check your form", 5, "text-danger");
    }
  };
}
function actionEdit(product) {
  window.location.href = MProduct.endpoints.edit.replace("{id}", product.id);
}
function actionDelete(product) {
  var content = $("<div>").append("<p>Are you sure want to delete this product?</p>").append("<p>Details as below</p>").append($("<table class='table table-bordered'>").append($("<tr style='vertical-align: baseline;'>").append("<td>id</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.id || "", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>title</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.title || "", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>price</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.price || 0, "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>category</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.category || '', "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>description</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.description || "", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>status</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.status === 1 ? "Publish" : "Unpublish", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>post_date</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.post_date || "-", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>update_date</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.update_date || "-", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>author_id</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.author_id || "-", "</p></td>")), $("<tr style='vertical-align: baseline;'>").append("<td>author_name</td>").append("<td><p style=\"word-break: break-all; margin:0;\">".concat(product.author_name || "-", "</p></td>"))));
  __.dialog.danger("Are you sure?", content).then(function () {
    __.http.post(MProduct.endpoints["delete"], {
      id: product.id
    }).then(function (res) {
      if (res.code === 200) {
        __.toast("Success delete product", 5, "text-success");
        MProduct.render();
      } else throw new Error(res.message);
    })["catch"](function () {
      __.toast("Failed to delete product", 5, "text-danger");
    });
  })["catch"](function () {
    __.toast("Action cancelled", 5, "text-info");
  });
}

/******/ })()
;