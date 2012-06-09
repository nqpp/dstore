<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_email extends CI_Model {

  public $emailID;
  public $searchStr;
  public $attachmentPath;

  function __construct() {
	$this->pk = 'emailID';
	$this->fields = $this->fields();
    parent::__construct();
    $this->load->library('Email_handler');
    $this->attachmentPath = realpath(BASEPATH.'../artwork').'/';
  }

  private function fields() {
    return array(
      'to',
      'cc',
      'bcc',
      'from',
      'name',
      'subject',
      'message',
      'createdAt',
      'createdBy',
      'sentAt',
      'attachments'
    );
  }

  function fetch() {

    $this->setSort('createdAt DESC');
    $this->db->select("DATE_FORMAT(`createdAt`,'%e %b %Y') as 'createdDate'", false);
    $this->db->select("DATE_FORMAT(`sentAt`,'%e %b %Y') as 'sentDate'", false);

	return parent::fetch();
  }

  function get() {

    if(!$this->emailID) throw new Exception('No ID supplied.');

    $this->db->select("DATE_FORMAT(`emails`.`createdAt`,'%e %b %Y') as 'createdDate'",false);
    $this->db->select("DATE_FORMAT(`emails`.`sentAt`,'%e %b %Y') as 'sentDate'",false);
    $this->db->select("DATE_FORMAT(`emails`.`sentAt`,'%e %b %Y %k:%i') as 'sentDateTime'",false);

	$row = parent::get();
    $row->attachments = $this->strToArray($row->attachments);

    return $row;
  }

  function add() {

    $this->createdAt = date('Y-m-d H:i:s');

    if ($this->input->post('attachments')) {
      $this->attachments = serialize($this->input->post('attachments'));
    }

    parent::add();

  }

  function update() {

    if ($this->input->post('attachments')) {
      $this->attachments = serialize($this->input->post('attachments'));
    }
    else {
      $this->attachments = '';
    }
    
	parent::update();
	
  }

  function delete() {

    $row = $this->get();
    $att = $this->formatAttachments($row->attachments);
    $this->deleteTmpMedia($att);

    parent::delete();
	
  }

//  function deleteForSchemaID() {
//
//    if(!$this->schemaID) throw new Exception('No schemaID supplied.[m_email:deleteForSchemaID]');
//    if(!$this->schemaName) throw new Exception('No schemaName supplied.[m_email:deleteForSchemaID]');
//
//    $this->db->where('schemaID',$this->schemaID);
//    $this->db->where('schemaName',$this->schemaName);
//    $data = $this->db->get('emails');
//    
//    if (!$data->num_rows()) return;
//
//    $row = $data->row();
//    $att = $this->formatAttachments($row->attachments);
//    $this->deleteTmpMedia($att);
//
//
//    $this->db->where('emailID',$row->emailID);
//    $this->db->delete('emails');
//  }

  function sort() {
    
	$sort = $this->input->get('sort');

	if ($sort) {
	  $dir = 'asc';

	  if (substr($sort,0,1) == '-') {
		$dir = 'desc';
		$sort = substr($sort,1);
	  }

	  $this->db->order_by($sort,$dir);
	}
  }

  function search() {

    if (!$this->searchStr) return;

    $sql = "(";
    $sql .= "`subject` LIKE '%{$this->searchStr}%'";
    $sql .= " OR `message` LIKE '%{$this->searchStr}%'";
    $sql .= " OR `email_to` LIKE '%{$this->searchStr}%'";
    $sql .= " OR `email_cc` LIKE '%{$this->searchStr}%'";
    $sql .= " OR `email_bcc` LIKE '%{$this->searchStr}%'";
    $sql .= ")";

    $this->db->where($sql);
    
  }

  function send() {

    if(!$this->emailID) throw new Exception('No ID supplied. [m_email:send]');

    // get record data
    $row = $this->get();
    $row->attachments =
	$this->formatAttachments($row->attachments);
	
	$data = (object) array_merge((array) $row, (array) $this->email_config);
	

    // send the email
/* no sending email in demo version
    $this->load->library('email_handler');
    $this->email_handler->initialise($row);
    $this->email_handler->send();
*/

	$this->sent();

    // erase any temp files uploded as attachments
    $this->deleteTmpMedia($row->attachments);
  }
  
  function sent() {
    
    $this->db->set('`sentAt`',date('Y-m-d H:i:s'));
    $this->db->where('`emailID`',$this->emailID);
    $this->db->update('emails');
	
  }

  // unserialise stored array
  private function strToArray($str) {
    if (trim($str) == '') return array();

    $array = unserialize($str);
    return $array;

  }

  // convert multi depth array to single depth with absolute path to each item
  function formatAttachments($att) {

    if (!count($att)) return;
    
    $formatted = array();

    foreach ($att as $dir=>$f) {

      $p = $this->attachmentPath.$dir.'/';

      foreach ($f as $filename) {
        if (!file_exists($p.$filename)) throw new Exception('File does not exist - '.$p.$filename.'.[m_email:formatAttachments]');
        $formatted[] = $p.$filename;
      }
    }

    return $formatted;
  }

  // delete any tmp upload fields in attachments array
  function deleteTmpMedia($attachments = array()) {

    if (!count($attachments)) return;

    foreach ($attachments as $attachment) {
      if ( strpos($attachment, '/tmp/') !== false AND file_exists($attachment)) {
        @unlink($attachment);
      }
    }

  }

  // remove a nominated attachment from the attachments field for a record
  function removeAttachment($filename = '',$dir = '') {

    if ($filename == '') throw new Exception('Filename not supplied to remove attachment.');
    if ($dir == '') throw new Exception('Filename directory not supplied to remove attachment.');

    $this->db->where('`emailID`',$this->emailID);
    $row = $this->db->get('emails')->row();

    if (!$row) throw new Exception('no record found for emailID');

    $row->attachments = $this->strToArray($row->attachments);
    
    if (! count($row->attachments) OR !isset($row->attachments[$dir])) return;

    $i = array_search($filename, $att[$dir]);

    if ($i === false) return;

    unset($att[$dir][$i]);
    if (! count($att[$dir])) unset($att[$dir]);

    if (! count($att)) {
      $this->db->set('attachments','');
    }
    else {
      $this->db->set('attachments', serialize($att));
    }

    $this->db->where('emailID',$this->emailID);
    if (! $this->db->update('emails')) throw new Exception('record was not updated');
  }

}
// EOF