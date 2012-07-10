<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ==========================================================================
 * CLIENT AUTO LOAD
 * 
 * Files to load via http request.
 * Includes CSS and Javascript files
 * ==========================================================================
 */

$config['client_autoload']['jsFiles'] = array(
	"/scripts/jquery-1.7.2.min.js",
	"/scripts/underscore.js",
	"/scripts/backbone.js",
	"/scripts/bootstrap.js",
	"/scripts/app.js"
);

$config['client_autoload']['cssFiles'] = array();

//EOF