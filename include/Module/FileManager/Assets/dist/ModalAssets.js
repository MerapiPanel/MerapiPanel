/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./include/module/FileManager/assets/src/ModalAssets.ts":
/*!**************************************************************!*\
  !*** ./include/module/FileManager/assets/src/ModalAssets.ts ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\nvar __ = window.__;\nvar FileManager = {\n  endpoints: {\n    upload: null,\n    fetch: null,\n    \"delete\": null\n  }\n};\n__.FileManager = new Proxy(FileManager, {\n  get: function get(target, name) {\n    if (target[name]) {\n      return target[name];\n    }\n    return null;\n  },\n  set: function set(target, name, value) {\n    throw new Error(\"Error: Can't change FileManager object\");\n  }\n});\n\n\n//# sourceURL=webpack://merapipanel/./include/module/FileManager/assets/src/ModalAssets.ts?");

/***/ })

/******/ 	});
/************************************************************************/
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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./include/module/FileManager/assets/src/ModalAssets.ts"](0, __webpack_exports__, __webpack_require__);
/******/ 	
/******/ })()
;