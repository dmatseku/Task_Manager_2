/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/task/list.js":
/*!***********************************!*\
  !*** ./resources/js/task/list.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $("#search").submit(function () {
    var form = $(this);
    var sendData = form.serialize();
    var searchLine = form.find("input:text");
    searchLine.prop("disabled", true);
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: sendData
    }).done(function (data) {
      $('#tbody').html(data);
      searchLine.removeClass('is-invalid');
      searchLine.prop("disabled", false);
    }).fail(function (jqXHR, textStatus) {
      console.log('Search error');
      searchLine.addClass('is-invalid');
      searchLine.prop("disabled", false);
    });
    return false;
  });
  $(".next-status").submit(function () {
    var form = $(this);
    var sendData = form.serialize();
    var statusButton = form.find("button");
    var deleteButton = form.closest(".cell-actions").find(".delete").find("button");
    var statusCell = form.closest(".list-group-item").find(".cell-status");
    statusButton.prop('disabled', true);
    deleteButton.prop('disabled', true);
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: sendData
    }).done(function (data) {
      statusCell.removeClass('bg-created bg-began bg-finished');
      statusButton.removeClass('btn-begin btn-finish');

      switch (data['status']) {
        case 1:
          statusCell.addClass('bg-created');
          statusCell.text("Begin in " + data['begin_in']);
          statusButton.addClass('btn-begin');
          statusButton.find('span').text("Begin");
          break;

        case 2:
          statusCell.addClass('bg-began');
          statusCell.text("Finish in " + data['finish_in']);
          statusButton.addClass('btn-finish');
          statusButton.find('span').text("Finish");
          break;

        case 3:
          statusCell.addClass('bg-finished');
          statusCell.text("Finished");
          form.remove();
          break;
      }

      statusButton.prop('disabled', false);
      deleteButton.prop('disabled', false);
    }).fail(function (jqXHR, textStatus) {
      console.log("Next status error");
      statusButton.prop('disabled', false);
      deleteButton.prop('disabled', false);
    });
    return false;
  });
  $('.delete').submit(function () {
    var form = $(this);
    var sendData = form.serialize();
    var statusButton = form.closest(".cell-actions").find(".next-status").find("button");
    var deleteButton = form.find("button");
    var listItem = form.closest(".list-group-item");
    statusButton.prop('disabled', true);
    deleteButton.prop('disabled', true);
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: sendData
    }).done(function (data) {
      if (data['success']) {
        listItem.remove();
      } else {
        statusButton.prop('disabled', false);
        deleteButton.prop('disabled', false);
      }
    }).fail(function (jqXHR, textStatus) {
      console.log('Delete error');
      statusButton.prop('disabled', false);
      deleteButton.prop('disabled', false);
    });
    return false;
  });
});

/***/ }),

/***/ 1:
/*!*****************************************!*\
  !*** multi ./resources/js/task/list.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/kamikoto/Projects/web/php/task_manager_2/resources/js/task/list.js */"./resources/js/task/list.js");


/***/ })

/******/ });