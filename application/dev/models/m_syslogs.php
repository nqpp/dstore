<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_syslogs extends CI_Model {

  var $path = false;
  var $filename;

  function __construct() {
    parent::__construct();
    $this->path = realpath(APPPATH.'logs').'/';
  }

  function fetch() {

    $data = scandir($this->path,1);
    $result = array();
    foreach ($data as $filename) {
      if (strpos($filename,'.') === 0 OR $filename == 'index.html')
        continue;

      $result[] = reset(explode('.',$filename));
    }

    return $result;
  }

  function get() {

    if (!$this->filename) throw new Exception('No filename supplied. [m_syslog:get]');

    $filename = $this->path.$this->filename.'.php';

    if (!file_exists($filename)) throw new Exception('File does not exist. [m_syslog:get]');

    return file_get_contents($filename);
  }

  function delete() {

    if (!$this->filename) throw new Exception('No filename supplied. [m_syslog:get]');

    $filename = $this->path.$this->filename.'.php';

    if (file_exists($filename))
      unlink($filename);
  }

}
// EOF