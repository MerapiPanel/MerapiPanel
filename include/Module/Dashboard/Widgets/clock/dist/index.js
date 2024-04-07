/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./include/Module/dashboard/Widgets/clock/src/style.scss":
/*!***************************************************************!*\
  !*** ./include/Module/dashboard/Widgets/clock/src/style.scss ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************************!*\
  !*** ./include/Module/dashboard/Widgets/clock/src/index.js ***!
  \*************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./include/Module/dashboard/Widgets/clock/src/style.scss");

var dialLines = document.getElementsByClassName('diallines');
var clockEl = document.getElementsByClassName('clock')[0];
for (var i = 1; i < 60; i++) {
  clockEl.innerHTML += "<div class='diallines'></div>";
  dialLines[i].style.transform = "rotate(" + 6 * i + "deg)";
}
function clock() {
  var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    d = new Date(),
    h = d.getHours(),
    m = d.getMinutes(),
    s = d.getSeconds(),
    date = d.getDate(),
    month = d.getMonth() + 1,
    year = d.getFullYear(),
    hDeg = h * 30 + m * (360 / 720),
    mDeg = m * 6 + s * (360 / 3600),
    sDeg = s * 6,
    hEl = document.querySelector('.hour-hand'),
    mEl = document.querySelector('.minute-hand'),
    sEl = document.querySelector('.second-hand'),
    dateEl = document.querySelector('.date'),
    dayEl = document.querySelector('.day');
  var day = weekday[d.getDay()];
  if (month < 9) {
    month = "0" + month;
  }
  hEl.style.transform = "rotate(" + hDeg + "deg)";
  mEl.style.transform = "rotate(" + mDeg + "deg)";
  sEl.style.transform = "rotate(" + sDeg + "deg)";
  dateEl.innerHTML = date + "/" + month + "/" + year;
  dayEl.innerHTML = day;
}
setInterval(function () {
  return clock();
}, 100);
})();

/******/ })()
;