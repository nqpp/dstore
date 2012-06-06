<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_users extends MM_Model {

  private $confirm_password;
  private $password;
  
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
	
    $pw = $this->passwords_match();
	parent::add();

  }

  function update() {
	
    if (!$this->id) throw new Exception('No ID supplied.');

//    $this->postToVar();

//    $pw = $this->passwords_match();
//    if (!$pw) $this->passwd = false;
    
    $this->passwords_match();
    $this->postToVar();
    $this->dbSetVars();

    $this->db->where('userID',(int)$this->id);
    $this->db->update('users');

  }
  
  private function passwords_match() {
	
    $pw = trim($this->input->post('password'));
    $cpw = trim($this->input->post('confirm_password'));
//	$cpw = trim($this->confirm_passwd);
	$this->passwd = false;
	$this->confirm_passwd = false;

    if($pw) {
//print
      if (!$cpw) throw new Exception('No confirm password supplied.');

      if($pw == $cpw) {
        $this->salt = $this->generate_salt();
        $this->passwd = $this->salt_password($pw, $this->salt);
      }
      else {
        throw new Exception('Supplied passwords do not match.');
      }

    }
	
//	return $pw;
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

  function set_passwd($id,$passwd) {

    $salt = $this->generate_salt();
    $salted = $this->salt_passwd($passwd,$salt);

    $this->db->set('salt',$salt);
    $this->db->set('passwd',$salted);
    $this->db->where('id',(int)$id);
    return $this->db->update('users');
  }

  function authenticate() {

	$this->email = $this->input->post('email_address');
	$this->password = $this->input->post('password');
	
	$result = $this->fetch();
	
	if ( count($result) != 1) throw new Exception('Bad username',0);
	
	$row = reset($result);

    if(!$this->compare_passwords($this->password, $row->password, $row->salt)) {
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

  
  function reset_password($email) {

    $this->db->select('userID');
    $this->db->where('email',$email);
    $data = $this->db->get('users');

    if($data->num_rows() == 1) {
      $data = $data->row();
    } else {
      return FALSE;
    }

    $id = $data->id;

    // gen new passwd
   $passwd = $this->generate_salt();
   $this->set_passwd($id,$passwd);

   return $passwd;
  }



}