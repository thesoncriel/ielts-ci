<?
class List_model2 extends CI_Model{
	public function __construct(){
		parent::__construct();

		$this->load->database();
	}
	public function get_list($koreanname = false){

		$query = $this->db->get("V_IELTS_INGLIST");
		return $query->row_array();
	}
}
?>