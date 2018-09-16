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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "/dist/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
__webpack_require__(2);

/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports) {

const $ = jQuery.noConflict();

var hourHeight = 0;

/*
 * Document ready functions
 */
$(document).ready(function () {
    /*--------------------------------*\
        HEADER FUNCTIONS
    \*--------------------------------*/

    /*
     * Add task modal
     */
    $('#add-task-button').click(function () {
        $('#add-task-modal').addClass('active');
        $('body').append('<div class="overlay"></div>');
        $('.overlay').fadeIn(500);
    });

    $('#add-task-button').keypress(function () {
        $('#add-task-button').click();
    });

    $('#close-task-modal').click(function () {
        $('#add-task-modal').removeClass('active');
        $('.overlay').fadeOut(500);
        setTimeout(function () {
            $('.overlay').remove();
        }, 500);
    });

    $('#close-task-modal').keypress(function () {
        $('#close-task-modal').click();
    });

    $('#close-event-modal').click(function () {
        $('.event-modal').fadeOut(500);
        $('.overlay').fadeOut(500);
        setTimeout(function () {
            $('.overlay').remove();
        }, 500);
    });

    $('#close-event-modal').keypress(function () {
        $('#close-event-modal').click();
    });

    $('.overlay').click(function () {
        $('#add-task-modal').removeClass('active');
        $('.event-modal').fadeOut(500);
        $('.overlay').fadeOut(500);
        setTimeout(function () {
            $('.overlay').remove();
        }, 500);
    });

    $('#add-task-duration').durationPicker({
        hours: {
            label: 'hours',
            min: 0,
            max: 24
        },
        minutes: {
            label: 'minutes',
            min: 0,
            max: 60
        }
    });

    $('#add-task-deadline').datepicker({
        prevText: "<",
        nextText: ">",
        dateFormat: "yy-mm-dd"
    });

    $('#scheduled-checkbox').change(function () {
        if ($(this).prop('checked') == true) {
            $('#add-task-form .unique-fields').fadeOut(400);
            setTimeout(function () {
                $('#add-event-form .unique-fields').fadeIn(400);
            }, 400);
            $('#styled-scheduled-checkbox').addClass('active');
        } else {

            $('#add-event-form .unique-fields').fadeOut(400);
            setTimeout(function () {
                $('#add-task-form .unique-fields').fadeIn(400);
            }, 400);
            $('#styled-scheduled-checkbox').removeClass('active');
        }
    });

    $('#styled-scheduled-checkbox').click(function () {
        $('#scheduled-checkbox').click();
    });

    $('#styled-scheduled-checkbox').keypress(function () {
        $('#scheduled-checkbox').click();
    });

    $('#add-task-name').change(function () {
        $('#add-event-name').val($(this).val());
    });

    $('#add-event-name').change(function () {
        $('#add-task-name').val($(this).val());
    });

    $('#add-event-start-date').datepicker({
        prevText: "<",
        nextText: ">",
        dateFormat: "yy-mm-dd"
    });

    $('#add-event-start-time').durationPicker({
        hours: {
            label: ':',
            min: 0,
            max: 24
        },
        minutes: {
            label: '',
            min: 0,
            max: 60
        }
    });

    $('#add-event-end-date').datepicker({
        prevText: "<",
        nextText: ">",
        dateFormat: "yy-mm-dd"
    });

    $('#add-event-end-time').durationPicker({
        hours: {
            label: ':',
            min: 0,
            max: 24
        },
        minutes: {
            label: '',
            min: 0,
            max: 60
        }
    });

    // end header functions

    /*--------------------------------*\
        EVENTS / TASKS FUNCTIONS
    \*--------------------------------*/
    hourHeight = $('.hour').eq(0).outerHeight();

    /*
     * Starred events
     */
    $('.event .pentagram').click(function () {
        $(this).toggleClass('inactive');
        if ($(this).hasClass('inactive')) {
            $(this).parent().parent().attr('data-starred', 'false');
        } else {
            $(this).parent().parent().attr('data-starred', 'true');
        }
    });

    $('.event .pentagram').keypress(function () {
        $(this).click();
    });

    /*
     * Position events
     */
    $('.event').each(function () {
        position_event($(this));
    });

    /*
     * Event modal
     */
    $('.event-title').click(function () {
        $('body').append('<div class="overlay"></div>');
        $('.overlay').fadeIn(500);
        $('.event-modal').fadeIn(500);
        clear_modal();
        populate_modal($(this).parent().parent());
    });

    // end events / tasks functions


    /*--------------------------------*\
        TASK VIEW FUNCTIONS
    \*--------------------------------*/
    $('#view-tasks').click(function () {
        $('#view-task-modal').addClass('active');
        $('body').append('<div class="overlay"></div>');
        $('.overlay').fadeIn(500);
    });

    $('#close-view-modal').click(function () {
        $('#view-task-modal').removeClass('active');
        $('.overlay').fadeOut(500);
        setTimeout(function () {
            $('.overlay').remove();
        }, 500);
    });

    // end task viewer functions
});

function position_event($event) {
    let time = $event.attr('data-time').split(":");
    let minute = time[1];
    let hour = time[0];

    let posY = hour * hourHeight + Math.floor(minute / 60 * hourHeight);
    console.log(posY);

    $event.css('top', posY + 'px');
}

function clear_modal() {
    let modalFormInputs = $('.event-modal form input');
    modalFormInputs.each(function () {
        $(this).val("");
    });
}

function populate_modal($event) {
    let starred = $event.attr('data-starred');
    let title = $event.attr('data-title');
    let startTime = $event.attr('data-time');
    let startDate = $event.attr('data-date');
    let endDate = $event.attr('data-end-date');
    let endTime = $event.attr('data-end-time');
    let location = $event.attr('data-location');
    let id = $event.attr('data-id');

    if (starred == 'true') {
        $('.event-modal .pentagram').removeClass('inactive');
    } else {
        $('.event-modal .pentagram').addClass('inactive');
    }

    $('#update-name').val(title);
    $('#update-start-date').val(startDate);
    $('#update-start-time').val(startTime);
    $('#update-end-date').val(endDate);
    $('#update-end-time').val(endTime);
    $('#update-location').val(location);
    $('#update-id').val(id);
    $('#update-identifier').prop('checked', true);
}

/***/ })
/******/ ]);
//# sourceMappingURL=main.js.map