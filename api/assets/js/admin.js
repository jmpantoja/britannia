// Globalize jquery
import jQuery from 'jquery';
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
// Configure momentJS locale
import moment from "moment"
import "readmore-js"
import "masonry-layout"

import "eonasdan-bootstrap-datetimepicker"
import "trumbowyg";

import "jquery-toast-plugin";
import "bootstrap-toggle";


import URI from "urijs";
import {Calendar} from "@fullcalendar/core";
import interaction from "@fullcalendar/interaction";
import timeGrid from "@fullcalendar/timegrid";
import resourceTimeGrid from "@fullcalendar/resource-timegrid";
import dayGrid from "@fullcalendar/daygrid";
import resourceDayGrid from "@fullcalendar/resource-daygrid";
// Custom
import "./admin/Admin"
import "./admin/sidebar"
import "./admin/jquery.confirmExit"
import "./admin/treeview"


import "./widgets/multiselect";
import "./widgets/toggle_field";
import "./widgets/changer_tab";
import "./widgets/course_information_dates";

import "./widgets/show_filters";

import "./widgets/toast";
import "./widgets/locked";
import "./widgets/locked_enrollment";

import "./widgets/mark_weight";
import "./widgets/mark_unit";
import "./widgets/mark_skill";

import "./widgets/attendance_date";

import "./widgets/pass_hours";
import "./widgets/pass_date";

import "./widgets/student_check";
import "./widgets/student_selector";
import "./widgets/term_date";
import "./widgets/template_selector";
import "./widgets/template_counter";

import "./widgets/invoice_total";

import "./widgets/calendar";
import "./widgets/planning";
import "./widgets/tutor";

import "./widgets/upload";
import "./widgets/upload_listener_thumb";
import "./widgets/upload_listener_link";


// Styles
import "../scss/admin.scss"

import trumbowyg_icons from 'trumbowyg/dist/ui/icons.svg'

global.$ = jQuery;
global.jQuery = jQuery;

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

moment.locale(languageMoment)

global.moment = moment;

// Load momentJS locale component
import("moment/locale/" + languageMoment + '.js')
  .catch('failed to load language component for momentJS')

global.URI = URI;

global.Calendar = Calendar;

global.interaction = interaction;

global.timeGrid = timeGrid;

global.resourceTimeGrid = resourceTimeGrid;

global.dayGrid = dayGrid;

global.resourceDayGrid = resourceDayGrid;


$(function () {
  $('[data-toggle="popover"]').popover();


  $('textarea.jodit').trumbowyg({
    svgPath: trumbowyg_icons,
    btns: [['strong', 'em', 'underline', 'del'], ['unorderedList', 'orderedList'], ['link'], ['fullscreen']],
    autogrow: true
  });

});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
