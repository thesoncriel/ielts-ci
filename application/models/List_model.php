<?
class List_model extends CI_Model{
	public function __construct(){
		parent::__construct();

		$this->load->database();
	}
	public function get_list($koreanname = false){
		if ($koreanname === false){
			$query = $this->db->get("V_IELTS_INGLIST");

			return $query->result_array();
		}

		$query = $this->db->get_where('V_IELTS_INGLIST', array('koreanname' => $koreanname));

		return $query->row_array();
	}
}
?>