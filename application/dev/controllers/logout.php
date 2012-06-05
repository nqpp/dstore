<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MM_Controller {

  function __construct() {
	parent::__construct();
  }

  function renderHTML() {

    $this->user->kill();
    die(header('Location: /login.html'));
  }
}
// EOF