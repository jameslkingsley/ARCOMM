/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.Tether = require('tether');
// window.Vue = require('vue');
// window.axios = require('axios');

require('jquery-ui-bundle');
require('bootstrap');
require('jquery.waitforimages');
require('dropzone');
require('magnific-popup');

window.collect = require('collect.js');

window.Tribute = require('tributejs');

import Mentions from './laravel-mentions';
window.Mentions = Mentions;

// window.axios.defaults.headers.common = {
//     'X-CSRF-TOKEN': window.Laravel.csrfToken,
//     'X-Requested-With': 'XMLHttpRequest'
// };
