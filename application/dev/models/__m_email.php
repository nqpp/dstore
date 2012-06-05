<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_email extends CI_Model {

  public $emailID;
  public $searchStr;
  public $attachmentPath;

  function __construct() {
    parent::__construct();
    $this->init();
    $this->load->library('Email_handler');
    $this->attachmentPath = realpath(BASEPATH.'../artwork').'/';
  }

  // initialise properties
  private function init() {
    foreach ($this->fields() as $f) {
      $this->$f = false;
    }
  }

  // db field names
  private function fields() {
    return array(
      'email_to',
      'email_cc',
      'email_bcc',
      'email_from',
      'email_name',
      'subject',
      'message',
      'createdAt',
      'sentAt',
      'attachments',
      'schemaName',
      'schemaID',
      'schemaSubID',
      'followUpReq',
      'priority'
    );
  }

  // set class properties to corresponding POST value if not already set
  private function postToVar() {
    foreach ($this->fields() as $f) {
      if ($this->$f === false) $this->$f = $this->input->post($f);
    }
  }

  // set properties per a supplied object
  function setproperties($object) {

    foreach ($object as $key=>$value) {
      $this->$key = $value;
    }

  }

  // get array of records
  function fetch() {

    if(!$this->schemaName) throw new Exception('No Schema Name supplied.');
    if(!$this->schemaID) throw new Exception('No Schema ID supplied.');

    $this->db->select('`emails`.*');
    $this->db->select("DATE_FORMAT(`createdAt`,'%e %b %Y') as 'createdDate'", false);
    $this->db->select("DATE_FORMAT(`sentAt`,'%e %b %Y') as 'sentDate'", false);
    $this->db->where('`schemaName`',$this->schemaName);
    $this->db->where('`schemaID`',$this->schemaID);

    if ($this->schemaSubID) {
      $this->db->where('`schemaSubID`',$this->schemaSubID);
    }

    $this->db->order_by('`createdAt` DESC');

    return $this->db->get('emails')->result();
  }

  // get a record
  function get() {

    if(!$this->emailID) throw new Exception('No ID supplied.');

    $this->db->select('`emails`.*');
    $this->db->select("DATE_FORMAT(`emails`.`createdAt`,'%e %b %Y') as 'createdDate'",false);
    $this->db->select("DATE_FORMAT(`emails`.`sentAt`,'%e %b %Y') as 'sentDate'",false);
    $this->db->select("DATE_FORMAT(`emails`.`sentAt`,'%e %b %Y %k:%i') as 'sentDateTime'",false);
    $this->db->where('`emailID`',$this->emailID);
    $data = $this->db->get('emails');

    if (!$data->num_rows()) throw new Exception('No record for ID supplied.');

    $row = $data->row();
    $row->attachments = $this->strToArray($row->attachments);

    return $row;
  }

  function add() {

    if(!$this->schemaName) throw new Exception('No Schema Name supplied.');
    if(!$this->schemaID) throw new Exception('No Schema ID supplied.');

    $this->createdAt = date('Y-m-d H:i:s');

    if ($this->input->post('attachments')) {
      $this->attachments = serialize($this->input->post('attachments'));
    }

    $this->postToVar();

    foreach ($this->fields() as $f) {
      if ($this->$f === false) continue;
      $this->db->set($f,$this->$f);
    }

    $this->db->insert('emails');
    $this->emailID = $this->db->insert_id();

  }

  function update() {

    if(!$this->emailID) throw new Exception('No ID supplied.');

    if ($this->input->post('attachments')) {
      $this->attachments = serialize($this->input->post('attachments'));
    }
    else {
      $this->attachments = '';
    }

    $this->postToVar();

    foreach ($this->fields() as $f) {
      if ($this->$f === false) continue;
      $this->db->set($f,$this->$f);
    }

    $this->db->where('emailID',$this->emailID);
    $this->db->update('emails');
  }

  function delete() {

    if(!$this->emailID) throw new Exception('No ID supplied.');

    $row = $this->get();
    $att = $this->formatAttachments($row->attachments);
    $this->deleteTmpMedia($att);


    $this->db->where('emailID',$this->emailID);
    $this->db->delete('emails');
  }

  function deleteForSchemaID() {

    if(!$this->schemaID) throw new Exception('No schemaID supplied.[m_email:deleteForSchemaID]');
    if(!$this->schemaName) throw new Exception('No schemaName supplied.[m_email:deleteForSchemaID]');

    $this->db->where('schemaID',$this->schemaID);
    $this->db->where('schemaName',$this->schemaName);
    $data = $this->db->get('emails');
    
    if (!$data->num_rows()) return;

    $row = $data->row();
    $att = $this->formatAttachments($row->attachments);
    $this->deleteTmpMedia($att);


    $this->db->where('emailID',$row->emailID);
    $this->db->delete('emails');
  }

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
    $row->attachments = $this->formatAttachments($row->attachments);

    // send the email
/* no sending email in demo version
    $this->load->library('email_handler');
    $this->email_handler->initialise($row);
    $this->email_handler->send();
*/

    // update record for send datetime
    $this->db->set('`sentAt`',date('Y-m-d H:i:s'));
    $this->db->where('`emailID`',$this->emailID);
    $this->db->update('emails');

    // erase any temp files uploded as attachments
    $this->deleteTmpMedia($row->attachments);
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