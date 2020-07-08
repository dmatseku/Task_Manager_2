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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/task/task.js":
/*!***********************************!*\
  !*** ./resources/js/task/task.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('#name-form').submit(function () {
    sendAjax($(this), $('#name-field'), function (form, field, data) {
      field.removeClass('border-danger');
      form.find('.invalid-feedback').remove();
    }, function (form, field, jqXHR) {
      var error = JSON.parse(jqXHR.responseText);
      field.addClass('border-danger');
      form.find('.invalid-feedback').remove();
      form.find('.form-group').append(buildError(error['errors']['name'][0], true));
    });
    return false;
  });
  $('#description-form').submit(function () {
    sendAjax($(this), $('#description-field'), function (form, field, data) {
      field.removeClass('border-danger');
      form.find('.invalid-feedback').remove();
    }, function (form, field, jqXHR) {
      var error = JSON.parse(jqXHR.responseText);
      field.addClass('border-danger');
      form.find('.invalid-feedback').remove();
      form.find('.form-group').append(buildError(error['errors']['description'][0], true));
    });
    return false;
  });
  $('#type-form').submit(function () {
    sendAjax($(this), $('#type-field'), function (form, field, data) {}, function (form, field, jqXHR) {});
    return false;
  });
  $('#btn-next-status').submit(function () {
    sendAjax($(this), $('#not-exists'), function (form, field, data) {
      updateStatusButton(data['status']);
    }, function (form, field, data) {});
    return false;
  });
  $('#dates-form').submit(function () {
    sendAjax($(this), $('#not-exists'), function (form, field, data) {
      $('#begin-field, #finish-field').removeClass('is-invalid');
      updateStatusButton(data['status']);
      form.find('.invalid-feedback').remove();
    }, function (form, field, jqXHR) {
      var error = JSON.parse(jqXHR.responseText);
      form.find('.invalid-feedback').remove();

      if ('begin_in' in error['errors']) {
        $('#begin-field').addClass('is-invalid').after(buildError(error['errors']['begin_in'][0], false));
      }

      if ('finish_in' in error['errors']) {
        $('#finish-field').addClass('is-invalid').after(buildError(error['errors']['finish_in'][0], false));
      }
    });
    return false;
  });
  $('#name-field').change(function () {
    $('#name-form').submit();
  });
  $('#description-field').change(function () {
    $('#description-form').submit();
  });
  $('#type-field').change(function () {
    $('#type-form').submit();
  });
  $('#begin-field, #finish-field').change(function () {
    $('#dates-form').submit();
  });
});

function lockPanel() {
  $('#buttons-panel').find('button, a').prop('disabled', true);
  $('#saving-status').text('Saving');
}

function unlockPanel(message) {
  $('#buttons-panel').find('button, a').prop('disabled', false);
  $('#saving-status').text(message);
}

function sendAjax(form, field, successFunction, errorFunction) {
  var sendData = form.serialize();
  field.prop("disabled", true);
  lockPanel();
  $.ajax({
    type: form.attr('method'),
    url: form.attr('action'),
    data: sendData
  }).done(function (data) {
    successFunction(form, field, data);
    unlockPanel('Saved');
    field.prop("disabled", false);
  }).fail(function (jqXHR, textStatus) {
    errorFunction(form, field, jqXHR);
    unlockPanel('Error');
    field.prop("disabled", false);
  });
}

function buildError(message, center) {
  return '<span class="invalid-feedback d-block ' + (center ? 'text-center ' : '') + 'mt-0" role="alert">\n' + '    <strong>' + message + '</strong>\n' + '</span>';
}

function updateStatusButton(status) {
  var form = $("#btn-next-status");
  var button = form.find('button');
  button.removeClass('btn-begin btn-finish');

  if (status > 2) {
    form.removeClass('d-block').addClass('d-none');
  } else {
    form.removeClass('d-none').addClass('d-block');
    form.css('display', 'block');

    if (status === 2) {
      button.addClass('btn-finish');
      button.find('span').text('Finish');
    } else {
      button.addClass('btn-begin');
      button.find('span').text('Begin');
    }
  }
}

/***/ }),

/***/ 2:
/*!*****************************************!*\
  !*** multi ./resources/js/task/task.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/kamikoto/Projects/web/php/task_manager_2/resources/js/task/task.js */"./resources/js/task/task.js");


/***/ })

/******/ });