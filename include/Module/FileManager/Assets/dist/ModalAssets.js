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
/*!**************************************************************!*\
  !*** ./include/Module/FileManager/Assets/src/ModalAssets.ts ***!
  \**************************************************************/
__webpack_require__.r(__webpack_exports__);
var __ = window.__;
var FileManager = {
  endpoints: {
    upload: null,
    fetch: null,
    "delete": null
  }
};
__.FileManager = new Proxy(FileManager, {
  get: function get(target, name) {
    if (target[name]) {
      return target[name];
    }
    return null;
  },
  set: function set(target, name, value) {
    throw new Error("Error: Can't change FileManager object");
  }
});

/******/ })()
;