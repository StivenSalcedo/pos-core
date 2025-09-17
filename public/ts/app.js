/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/ts/app.ts":
/*!*****************************!*\
  !*** ./resources/ts/app.ts ***!
  \*****************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



var __importDefault = this && this.__importDefault || function (mod) {
  return mod && mod.__esModule ? mod : {
    "default": mod
  };
};

Object.defineProperty(exports, "__esModule", ({
  value: true
}));

var modal_1 = __importDefault(__webpack_require__(/*! ./wireui/modal */ "./resources/ts/wireui/modal.ts"));

document.addEventListener('alpine:init', function () {
  window.Alpine.data('wireui_modal', modal_1["default"]);
});

/***/ }),

/***/ "./resources/ts/wireui/modal.ts":
/*!**************************************!*\
  !*** ./resources/ts/wireui/modal.ts ***!
  \**************************************/
/***/ ((__unused_webpack_module, exports) => {



function _toConsumableArray(arr) {
  return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
}

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return _arrayLikeToArray(arr);
}

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

Object.defineProperty(exports, "__esModule", ({
  value: true
}));

exports["default"] = function (options) {
  return {
    show: options.model,
    init: function init() {
      var _this = this;

      this.$watch('show', function (value) {
        _this.$el.dispatchEvent(new Event(value ? 'open' : 'close'));
        value ? document.body.classList.add('overflow-y-hidden') : document.body.classList.remove('overflow-y-hidden');
      });
    },
    close: function close() {
      this.show = false;
    },
    focusables: function focusables() {
      var selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
      return _toConsumableArray(this.$el.querySelectorAll(selector)).filter(function (el) {
        return !el.hasAttribute('disabled');
      });
    },
    firstFocusable: function firstFocusable() {
      return this.focusables()[0];
    },
    lastFocusable: function lastFocusable() {
      return this.focusables().slice(-1)[0];
    },
    nextFocusable: function nextFocusable() {
      return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable();
    },
    previousFocusable: function previousFocusable() {
      return this.focusables()[this.previousFocusableIndex()] || this.lastFocusable();
    },
    nextFocusableIndex: function nextFocusableIndex() {
      if (!document.activeElement) return -1;
      return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1);
    },
    previousFocusableIndex: function previousFocusableIndex() {
      if (!document.activeElement) return -1;
      return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1;
    }
  };
};

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/************************************************************************/
/******/
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/ts/app.ts");
/******/
/******/ })()
;
