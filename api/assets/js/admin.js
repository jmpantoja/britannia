// Globalize jquery
import jQuery from 'jquery';
global.$ = jQuery;
global.jQuery = jQuery;

// Vendors
import "admin-lte"
import "bootstrap"

import "jquery-form"
import "jquery-ui"
import "jquery.scrollto"
import "jquery-slimscroll"

import "x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min"

import "icheck"
import "waypoints/lib/jquery.waypoints"
import "waypoints/lib/shortcuts/sticky.min"
import "select2"

// Loading langugage files for select2
//let fallbackLocale = window.navigator.language;
// let languageSelect2 = document.documentElement.getAttribute('select2Locale') || fallbackLocale
// let languageMoment = document.documentElement.getAttribute('momentLocale') || fallbackLocale

let languageSelect2 = 'es'
let languageMoment = 'es'


import(`select2/select2_locale_${languageSelect2}.js`).then(() => {
  // set <html lang="{{language}}">
  document.documentElement.setAttribute('lang', languageSelect2)
}).catch('failed to import select2 locale')

// Configure momentJS locale
import("moment").then(moment => {
  moment.locale(languageMoment)
}).catch('failed to configure momentJS locale')

// Load momentJS locale component
import("moment/locale/" + languageMoment + '.js')
  .catch('failed to load language component for momentJS')

import "readmore-js"
import "masonry-layout"

import "eonasdan-bootstrap-datetimepicker"
import "trumbowyg";

import "jquery-toast-plugin";

// Custom
import "./admin/Admin"
import "./admin/sidebar"
import "./admin/jquery.confirmExit"
import "./admin/treeview"

import "./widgets/multiselect";
import "./widgets/toggle_field";
import "./widgets/toast";


// Styles
import "../scss/admin.scss"

import trumbowyg_icons from 'trumbowyg/dist/ui/icons.svg'

$(function() {
  $('[data-toggle="popover"]').popover();


  $('textarea.jodit').trumbowyg({
    svgPath: trumbowyg_icons,
    btns: [['strong', 'em', 'underline', 'del'], ['unorderedList', 'orderedList'], ['link'], ['fullscreen']],
    autogrow: true
  });

  console.log('document ready!');
});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
