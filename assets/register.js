// custom code for your register page

// loads the jquery package from node_modules

import $ from 'jquery';
import greet from './welcomeToEncore';

$(document).ready(function() {
    $('body').prepend('<h1>'+greet('Everybody')+'</h1>');
});
