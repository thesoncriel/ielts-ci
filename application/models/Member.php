<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Member extends CI_Model {
	var $userid = '';
	var $pass = '';
	var $name = '';
	
	function __construct() {
		parent::__construct();
	}

	function get_member_list() {
		//$query = $this->db->query("SELECT SEQ, CP_CODE FROM EC_MEMBER WHERE seq = 94");
		$query = $this->db->query("SELECT MSG_BODY FROM UDS_LOG WHERE CMID = 20131216211222001521");

		$i=0;
		 foreach ($query->result() as $row)
		 {
			//$date['seq'] = $row->SEQ;
			//$date['cp_code'] = $row->CP_CODE;
			$date['msg_body'] = $row->MSG_BODY;
			$i++;
		 }
		 return $date;
	}
}