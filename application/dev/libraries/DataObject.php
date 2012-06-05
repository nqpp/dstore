<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class DataObject {
	
  protected $model;
  var $row;

  function __construct($row = array()) {
	$this->model = substr(get_class($this),3); // Find model name from class.
	$this->row = $row;
  }
}