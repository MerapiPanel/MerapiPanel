/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
/*!*************************************************************!*\
  !*** ./include/Module/dashboard/Assets/tools/WdgetEvent.ts ***!
  \*************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Wdget_EventDragStart: () => (/* binding */ Wdget_EventDragStart),
/* harmony export */   Wdget_EventDragStop: () => (/* binding */ Wdget_EventDragStop),
/* harmony export */   Wdget_EventDraggingIn: () => (/* binding */ Wdget_EventDraggingIn),
/* harmony export */   Wdget_EventDraggingMove: () => (/* binding */ Wdget_EventDraggingMove),
/* harmony export */   Wdget_EventDraggingOut: () => (/* binding */ Wdget_EventDraggingOut),
/* harmony export */   Wdget_EventDrop: () => (/* binding */ Wdget_EventDrop),
/* harmony export */   Wdget_EventMovingData: () => (/* binding */ Wdget_EventMovingData),
/* harmony export */   Wdget_EventSource: () => (/* binding */ Wdget_EventSource)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */
var Wdget_EventSource = /*#__PURE__*/_createClass(function Wdget_EventSource(el, entity) {
  _classCallCheck(this, Wdget_EventSource);
  this.el = el;
  this.entity = entity;
});
var Wdget_EventMovingData = /*#__PURE__*/_createClass(function Wdget_EventMovingData(index, position) {
  _classCallCheck(this, Wdget_EventMovingData);
  this.index = index;
  this.position = position;
});
var Wdget_EventDragStart = /*#__PURE__*/_createClass(function Wdget_EventDragStart(source, coordinate) {
  _classCallCheck(this, Wdget_EventDragStart);
  this.coordinate = coordinate;
  this.source = source;
  this.type = "drag:start";
});
var Wdget_EventDragStop = /*#__PURE__*/_createClass(function Wdget_EventDragStop(source, coordinate) {
  _classCallCheck(this, Wdget_EventDragStop);
  this.coordinate = coordinate;
  this.source = source;
  this.type = "drag:start";
});
var Wdget_EventDraggingIn = /*#__PURE__*/_createClass(function Wdget_EventDraggingIn(source, target, coordinate) {
  _classCallCheck(this, Wdget_EventDraggingIn);
  this.coordinate = coordinate;
  this.source = source;
  this.target = target;
  this.type = "dragging:in";
});
var Wdget_EventDraggingOut = /*#__PURE__*/_createClass(function Wdget_EventDraggingOut(source, coordinate) {
  _classCallCheck(this, Wdget_EventDraggingOut);
  this.coordinate = coordinate;
  this.source = source;
  this.type = "dragging:out";
});
var Wdget_EventDraggingMove = /*#__PURE__*/_createClass(function Wdget_EventDraggingMove(source, target, coordinate) {
  _classCallCheck(this, Wdget_EventDraggingMove);
  this.coordinate = coordinate;
  this.source = source;
  this.target = target;
  this.type = "dragging:move";
});
var Wdget_EventDrop = /*#__PURE__*/_createClass(function Wdget_EventDrop(source, coordinate, index, target) {
  _classCallCheck(this, Wdget_EventDrop);
  this.coordinate = coordinate;
  this.source = source;
  this.type = "drop";
  this.index = index;
  this.target = target;
});

/******/ })()
;