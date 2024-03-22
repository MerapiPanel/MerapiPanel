/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./merapi/Module/Editor/component/blocks/group/src/edit.js":
/*!*****************************************************************!*\
  !*** ./merapi/Module/Editor/component/blocks/group/src/edit.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Edit: () => (/* binding */ Edit)
/* harmony export */ });
var Edit = function Edit(props) {
  return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h1", null, "Group"));
};

/***/ }),

/***/ "./merapi/Module/Editor/component/blocks/group/src/view.js":
/*!*****************************************************************!*\
  !*** ./merapi/Module/Editor/component/blocks/group/src/view.js ***!
  \*****************************************************************/
/***/ (() => {



/***/ }),

/***/ "../modules/merapipanel/dist/editor/block/index.js":
/*!*********************************************************!*\
  !*** ../modules/merapipanel/dist/editor/block/index.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.registerBlock = exports.useProperty = exports.propertyName = void 0;
exports.propertyName = "@merapipanel\/editor\/block";
var useProperty = function useProperty() {
  if (!window[exports.propertyName]) {
    window[exports.propertyName] = {
      hooks: new Map(),
      map: new Map(),
      add: function add(name, block) {
        var _this = this;
        this.map.set(name, block);
        this.hooks.forEach(function (callback) {
          callback(_this.map, name, block);
        });
      },
      get: function get(id) {
        return window[exports.propertyName].map[id];
      }
    };
  }
  var property = window[exports.propertyName];
  return {
    add: function add(name, block) {
      property.add(name, block);
    },
    get: function get(id) {
      return property.get(id);
    },
    map: property.map,
    hook: function hook(name, callback) {
      property.hooks.set(name, callback);
    }
  };
};
exports.useProperty = useProperty;
var registerBlock = function registerBlock(id, block) {
  //console.log(id, block);
  (0, exports.useProperty)().add(id, block);
};
exports.registerBlock = registerBlock;

/***/ }),

/***/ "./merapi/Module/Editor/component/blocks/group/src/block.json":
/*!********************************************************************!*\
  !*** ./merapi/Module/Editor/component/blocks/group/src/block.json ***!
  \********************************************************************/
/***/ ((module) => {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"file:///F:/web/MerapiPanel/schema/block.json","name":"core/group","title":"group","editScript":"file:./edit.js","editStyle":"file:./edit.scss","index":"file:./index.js","view":"file:./view.js"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/create fake namespace object */
/******/ 	(() => {
/******/ 		var getProto = Object.getPrototypeOf ? (obj) => (Object.getPrototypeOf(obj)) : (obj) => (obj.__proto__);
/******/ 		var leafPrototypes;
/******/ 		// create a fake namespace object
/******/ 		// mode & 1: value is a module id, require it
/******/ 		// mode & 2: merge all properties of value into the ns
/******/ 		// mode & 4: return value when already ns object
/******/ 		// mode & 16: return value when it's Promise-like
/******/ 		// mode & 8|1: behave like require
/******/ 		__webpack_require__.t = function(value, mode) {
/******/ 			if(mode & 1) value = this(value);
/******/ 			if(mode & 8) return value;
/******/ 			if(typeof value === 'object' && value) {
/******/ 				if((mode & 4) && value.__esModule) return value;
/******/ 				if((mode & 16) && typeof value.then === 'function') return value;
/******/ 			}
/******/ 			var ns = Object.create(null);
/******/ 			__webpack_require__.r(ns);
/******/ 			var def = {};
/******/ 			leafPrototypes = leafPrototypes || [null, getProto({}), getProto([]), getProto(getProto)];
/******/ 			for(var current = mode & 2 && value; typeof current == 'object' && !~leafPrototypes.indexOf(current); current = getProto(current)) {
/******/ 				Object.getOwnPropertyNames(current).forEach((key) => (def[key] = () => (value[key])));
/******/ 			}
/******/ 			def['default'] = () => (value);
/******/ 			__webpack_require__.d(ns, def);
/******/ 			return ns;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!******************************************************************!*\
  !*** ./merapi/Module/Editor/component/blocks/group/src/index.js ***!
  \******************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block.json */ "./merapi/Module/Editor/component/blocks/group/src/block.json");
/* harmony import */ var _il4mb_merapipanel_block__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @il4mb/merapipanel/block */ "../modules/merapipanel/dist/editor/block/index.js");


(0,_il4mb_merapipanel_block__WEBPACK_IMPORTED_MODULE_1__.registerBlock)(_block_json__WEBPACK_IMPORTED_MODULE_0__.name, {
  edit: function edit() {
    return Promise.resolve(/*! import() */).then(__webpack_require__.bind(__webpack_require__, /*! ./edit.js */ "./merapi/Module/Editor/component/blocks/group/src/edit.js"));
  },
  view: function view() {
    return Promise.resolve(/*! import() */).then(__webpack_require__.t.bind(__webpack_require__, /*! ./view.js */ "./merapi/Module/Editor/component/blocks/group/src/view.js", 23));
  }
});
})();

/******/ })()
;