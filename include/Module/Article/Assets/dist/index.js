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
  !*** ./include/Module/Article/Assets/src/index.ts ***!
  \****************************************************/
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var __ = window.__;
var MArticle = __.Article;
if (!MArticle.config) {
  MArticle.config = {
    payload: {
      page: 1,
      limit: 10,
      search: ""
    },
    roles: {
      modify: true
    }
  };
}
if (!MArticle.config.roles) {
  MArticle.config.roles = {};
}
if (!MArticle.config.payload) {
  MArticle.config.payload = {
    page: 1,
    limit: 10,
    search: ""
  };
}
var roles = _objectSpread(_objectSpread({}, {
  modify: true
}), MArticle.config.roles);
var endpoints = MArticle.endpoints;
var payload = MArticle.config.payload;
$("#panel-subheader-search").on("submit", function (e) {
  e.preventDefault();
  payload.page = 1;
  payload.search = $(this).find("input").val();
  MArticle.render();
});
function createPagination(container, page, totalPages) {
  $(container).html("");
  // Define the number of pages to show before and after the current page
  var range = 3;

  // Determine start and end points for the pagination range
  var start = Math.max(1, page - range);
  var end = Math.min(totalPages, page + range);

  // Adjust start and end points if necessary to ensure that range remains constant
  if (end - start < 2 * range) {
    if (start === 1) {
      end = Math.min(totalPages, start + 2 * range);
    } else if (end === totalPages) {
      start = Math.max(1, end - 2 * range);
    }
  }

  // Add left offset if not on the first page
  if (page > 1) {
    $(container).append('<li class="page-item"><a class="page-link" href="#" data-page="1">&laquo;</a></li>');
  }

  // Add page links
  for (var i = start; i <= end; i++) {
    var liClass = i === page ? "page-item active" : "page-item";
    $(container).append($('<li class="' + liClass + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>'));
  }

  // Add right offset if not on the last page
  if (page < totalPages) {
    $(container).append('<li class="page-item"><a class="page-link" href="#" data-page="' + totalPages + '">&raquo;</a></li>');
  }

  // Add event listeners for page links
  $(container).find("a.page-link").on("click", function (e) {
    e.preventDefault();
    payload.page = parseInt($(this).data("page"));
    MArticle.render();
  });
}
MArticle.render = function () {
  if (this instanceof HTMLElement) {
    MArticle.el = this;
  }
  if (!endpoints.fetchAll) {
    throw new Error('Please provide an endpoint to fetch all articles');
  }
  __.http.get(endpoints.fetchAll, payload).then(function (res) {
    var _ref = res.data,
      articles = _ref.articles,
      totalPages = _ref.totalPages,
      totalResults = _ref.totalResults;
    if (payload.page > totalPages) {
      payload.page = totalPages;
      return MArticle.render();
    }
    $("#total-records").text("Total Records: ".concat(totalResults));
    createPagination($("#pagination"), payload.page, totalPages);
    setTimeout(function () {
      if ((articles || []).length > 0) {
        $(MArticle.el).html("").append((articles || []).map(function (article) {
          return createCard(article);
        }));
      } else {
        $(MArticle.el).html("").append($("<div class='text-muted text-center w-100 py-5 my-5 fs-2'>No articles found</div>"));
      }
    }, 600);
  })["catch"](function (err) {
    __.toast(err.message || "Something went wrong", 5, 'text-danger');
  });
};
function createCard(article) {
  var card = $("<div class='card card-body border-0 shadow-sm d-flex flex-column position-relative' style='width: 100%; max-width: 30rem;'>").append($("<div class='flex-grow-1'>").append($("<div class='d-flex'>").append(article.status ? '<i class="fa-solid fa-earth-americas"></i>' : '<i class="fa-solid fa-lock"></i>').append($("<h5 class=\"ms-1 card-title\">".concat(article.title || 'No Title', "</h5>")))).append("<p>".concat(article.description || '<span class="text-muted opacity-50">No Description</span>', "</p>"))).append($("<div>").append($("<button class='btn btn-sm btn-primary px-5 mb-2'>View</button>").on('click', function () {
    if (endpoints.view) {
      window.open(endpoints.view.replace('{id}', article.id), '_blank');
    } else {
      __.toast("No view endpoint found", 5, 'text-danger');
    }
  })).append($("<div class='d-flex gap-3 text-muted'>").append("<span>Update: ".concat(article.update_date || 'No Date', "</span>")).append("<span class='ms-auto'>".concat(article.author_name || 'No Author', "</span>")))).append(roles.modify == true ? $("<div class='dropdown position-absolute top-0 end-0'>").append($("<button class=\"btn dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">").append("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-three-dots-vertical\" viewBox=\"0 0 16 16\">")).append($("<div class=\"dropdown-menu dropdown-menu-end\">").append($("<a class=\"dropdown-item\" href=\"#\">Edit</a>").on('click', function () {
    if (endpoints.edit) {
      window.location.href = endpoints.edit.replace('{id}', article.id);
    } else {
      __.toast("No edit URL found", 5, 'text-danger');
    }
  })).append($("<a class=\"dropdown-item\" href=\"#\">Quick Edit</a>").on('click', function () {
    return QuickEdit(article);
  })).append($("<a class=\"dropdown-item text-danger\" href=\"#\">Delete</a>").on('click', function () {
    return ConfirmDelete(article);
  }))) : '');
  return card;
}
function QuickEdit(article) {
  var modal = null;
  if ($('#quick-edit-modal').length > 0) {
    modal = __.modal.from($('#quick-edit-modal'));
  } else {
    modal = __.modal.create("Edit Article", "");
    modal.el.attr('id', 'quick-edit-modal');
  }
  modal.dismiss = null;
  modal.clickOut = false;
  modal.action.negative = function () {
    modal.hide();
  };
  var form = $("<form class=\"needs-validation\">").append($("<div>").append($("<label class='mb-3 d-block'>").append("Title : ").append("<input type=\"text\" class=\"form-control\" name=\"title\" pattern=\"[a-zA-Z\\s]{5,}\" value=\"".concat(article.title || '', "\">")).append("<div class=\"invalid-feedback\">Please enter a title</div>")).append($("<label class='mb-3 d-block'>").append("Slug : ").append("<input type=\"text\" class=\"form-control\" name=\"slug\" value=\"".concat(article.slug || '', "\">")).append("<div class=\"invalid-feedback\">Please enter a slug</div>")).append($("<label class='mb-3 d-block'>").append("Keywords : ").append("<input type=\"text\" class=\"form-control\" name=\"keywords\" value=\"".concat(article.keywords || '', "\">")).append("<div class=\"invalid-feedback\">Please enter a keywords</div>")).append($("<label class='mb-3 d-block'>").append("Description : ").append("<textarea class=\"form-control\" name=\"description\">".concat(article.description || '', "</textarea>")).append("<div class=\"invalid-feedback\">Please enter a description</div>")).append($("<label class='mb-3 d-block'>").append("Category : ").append("<input type=\"text\" class=\"form-control\" name=\"category\" value=\"".concat(article.category || '', "\">")).append("<div class=\"invalid-feedback\">Please enter a category</div>")).append($("<label class='mb-3 d-block form-check'>").append("<input class='form-check-input' type=\"checkbox\" name=\"status\" ".concat(article.status ? 'checked' : '', ">")).append("<span class='form-check-label'>Publish</span>")));
  modal.content = form;
  modal.show();
  modal.action.positive = function () {
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    // let found_article = articles.find((a: any) => a.id === article.id);
    var update_article = _objectSpread(_objectSpread({}, article), form.serializeArray().reduce(function (obj, item) {
      obj[item.name] = item.value;
      if (item.name === 'status') obj[item.name] = item.value === 'on' ? 1 : 0;
      return obj;
    }, {
      status: 0
    }));

    // Object.keys(articles[articles.indexOf(article)]).forEach((key) => {
    //     articles[articles.indexOf(article)][key] = found_article[key];
    // })
    // render(container);

    if (!endpoints.update) {
      __.toast("No update endpoint found", 5, 'text-danger');
    }
    __.http.post(endpoints.update, update_article).then(function (res) {
      if (res.code === 200) {
        __.toast("Article updated", 5, 'text-success');
        MArticle.render();
      } else {
        __.toast(res.message || "Failed to update article", 5, 'text-danger');
      }
    })["catch"](function (err) {
      __.toast(err.message || "Failed to update article", 5, 'text-danger');
    });
  };
}
function ConfirmDelete(article) {
  __.dialog.danger("Are you sure?", $("<div>").append($("<div>Are you sure you want to delete this article?</div>").append($("<div class='py-3'>").append("<div class='fw-bold'>".concat(article.title, "</div>")).append("<div class='text-primary text-opacity-75'>".concat(article.id, "</div>")))).append("<p class='text-danger'>This action cannot be undone.</p>")).then(function () {
    if (!endpoints["delete"]) {
      __.toast("No delete endpoint found", 5, 'text-danger');
      return;
    }
    __.http.post(endpoints["delete"], {
      id: article.id
    }).then(function (res) {
      if (res.code == 200) {
        __.toast("Article deleted", 5, 'text-success');
        MArticle.render();
      } else {
        __.toast(res.message || "Failed to delete article", 5, 'text-danger');
      }
    })["catch"](function (err) {
      __.toast(err.message || "Failed to delete article", 5, 'text-danger');
    });
  })["catch"](function () {
    __.toast("Action cancelled", 5, 'text-info');
  });
}

/******/ })()
;