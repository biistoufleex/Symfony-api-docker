/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// loads the jquery package from node_modules
const $ = require('jquery');
global.$ = global.jQuery = $;

// import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'bootstrap/dist/css/bootstrap.min.css';

import 'datatables.net/js/jquery.dataTables.js';
import language from "datatables.net-plugins/i18n/fr-FR.mjs";
global.language = language;
