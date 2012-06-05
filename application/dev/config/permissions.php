<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Permissions Config
 * 
 * Add each controller as a parent. Use lower case as it would appear in url.
 * 
 * Add each REST process that is available.
 * 
 * Parent item is assumed as a GET.
 * 
 * Eg.
 * ---------------------------------------------------
 * 
 * $config['permission_schema']['widgets'] = array('GET','POST','PUT','DELETE');
 * 
 */

/* CORE SYSTEM ITEMS */
$config['permission_schema']['logout'] = array();
//$config['permission_schema']['make_permissions'] = array();
$config['permission_schema']['permissions'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['users'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['syslogs'] = array('GET','DELETE');
$config['permission_schema']['metas'] = array('GET','POST','PUT','DELETE');

/* ADDITIONAL ITEMS */
$config['permission_schema']['change_view'] = array('GET');
$config['permission_schema']['products'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['product_metas'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['product_images'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['store'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['clients'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['client_metas'] = array('GET','POST','PUT','DELETE');
$config['permission_schema']['supplier'] = array('GET','POST','PUT','DELETE');



