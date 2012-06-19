<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_users extends MM_Model {

  public $confirm_password;
  public $password;
  
  function __construct() {
	$this->pk = 'userID';
	$this->fields = $this->fields();
    parent::__construct();
	$this->active = 1;
  }


  // db field names
  private function fields() {
    return array(
      'firstName',
      'lastName',
      'email',
      'passwd',
      'salt',
      'adminGroup',
      'active'
    );
  }

  function fetch() {

    $this->setSort('firstName');
	return parent::fetch();
  }
  
  function fetchJoined() {
	
    $this->db->select('metas.metaValue as groupName');
	$this->db->join('metas','metaKey = adminGroup', 'left outer');
    $this->db->where('metas.schemaName','adminGroup');
	return $this->fetch();
  }
  
  function add() {
	
    $this->passwords_match();
	parent::add();

  }

  function update() {
	
    if (!$this->id) throw new Exception('No ID supplied.');

    $this->passwords_match();
    $this->postToVar();
    $this->dbSetVars();

    $this->db->where('userID',(int)$this->id);
    $this->db->update('users');

  }
  
  private function passwords_match() {
	
	if ($this->password) { // allows for set by JSON
	  $pw = $this->password;
	  $cpw = $this->confirm_password;	  
	}
	else {
	  $pw = trim($this->input->post('password'));
	  $cpw = trim($this->input->post('confirm_password'));
	}

	$this->passwd = false;
//	$this->confirm_password = false;

    if($pw) {

	  if (!$cpw) throw new Exception('No confirm password supplied.');

      if($pw == $cpw) {
        $this->salt = $this->generate_salt();
        $this->passwd = $this->salt_password($pw, $this->salt);
      }
      else {
        throw new Exception('Supplied passwords do not match.');
      }

    }
	
  }


  function salt_password($passwd, $salt) {

    $salted = hash_hmac('sha256', $passwd, $salt);
    return $salted;
  }


  function generate_salt() {

    $src = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_+';
    $salt = '';

    for($i = 0; $i < 10; $i++) {
      $salt .= $src[mt_rand(0,74)];
    }

    return $salt;
  }
  
  function generate_password() {
	return $this->generate_salt();
  }

  function set_passwd($userID, $password) {

    $salt = $this->generate_salt();
    $salted = $this->salt_password($password,$salt);

    $this->db->set('salt',$salt);
    $this->db->set('passwd',$salted);
    $this->db->where('userID',(int)$userID);
    if (!$this->db->update('users')) {
	  throw new Exception('An error occurred. Password was not reset');
	}

  }

  function authenticate() {

	$this->email = $this->input->post('email_address');
	$this->password = $this->input->post('password');
	
	$result = $this->fetch();
	
	if ( count($result) != 1) throw new Exception('Bad username',0);
	
	$row = reset($result);

    if(!$this->compare_passwords($this->password, $row->passwd, $row->salt)) {
      throw new Exception('Incorrect password', 1);
    }
	
	return $row;
	
  }

  function compare_passwords($user_pw,$db_pw,$salt) {

    $u = $this->salt_password($user_pw,$salt);
    $c = $db_pw;

    if($u == $c) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  
  function reset_password() {

	$this->email = $this->input->post('email_address');
	$result = $this->fetch();
	
	if(!count($result)) throw new Exception("Email address $email does not exist in the system.");

	$row = reset($result);
	// gen new passwd
	$row->password = $this->generate_password();
	$this->set_passwd($row->userID,$row->password);

	return $row;
  }



}
