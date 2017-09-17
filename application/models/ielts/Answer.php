<?
class Answer extends CI_Model{
	public function __construct(){
		parent::__construct();

		$this->load->database();
	}
	/*
	과거 테스트용 Legacy Code. 완료 후엔 삭제해도 됨.
	public function get_list($koreanname = false){
		if ($koreanname === false){
			$query = $this->db->get("V_IELTS_INGLIST");

			return $query->result_array();
		}

		$query = $this->db->get_where('V_IELTS_INGLIST', array('koreanname' => $koreanname));

		return $query->row_array();
	}
	*/
	public function load($sessionid){
		$query = $this->db->get_where( "IELTS_TEST_ANSWER", array( "SESSIONID" => $sessionid ) );
		$row = $query->row_array();
		
		if ( isset( $row ) === true ){
			return $row;
		}
		
		return array();
	}
	/*
	 * SQL GATE 에서 복사-붙여넣기 하고 array key-value로 바꿀 때
	 * notepad++ regex 사용기준
	 * ,|$
	 * " => "",\r\n
	 */
	public function save( $data ){
		$query = $this->db->query( "SELECT IELTS_TEST_ANSWER_SEQ.NEXTVAL AS ID FROM DUAL" );
		$row = $query->row_array();
		$id = $row["ID"];
		
		$mDef = array(
			"SEQ" 			=> $id,
			/*
			"USERID" 		=> "ielts_temp",
			"USERIP" 		=> "0.0.0.0",
			"SESSIONID" 	=> "0",
			"MODULE_TYPE" 	=> "G",
			"MODULE_NUM" 	=> "1",
			"COMPANYID" 	=> "0",
			"STATUS" 		=> "0",
			"L_ANSWER" 		=> "",
			"L_ANSWER_CHK" 	=> "0",
			"L_SEC" 		=> "0",
			"L_TIME" 		=> "00:00:00",
			"L_SCORE" 		=> "0",
			"R_ANSWER" 		=> "",
			"R_ANSWER_CHK" 	=> "0",
			"R_SEC" 		=> "0",
			"R_TIME" 		=> "00:00:00",
			"R_SCORE" 		=> "0",
			"SUM_SCORE" 	=> "0",
			 */
			"TESTDATE" 		=> date( "Y-m-d H:i:s" ),
			"STARTDATE" 	=> date( "Y-m-d H:i:s" ),
			"ENDDATE" 		=> date( "Y-m-d H:i:s" )
		);
		
		$mData = array_merge( $mDef, $data );
		
		$this->db->insert( "IELTS_TEST_ANSWER", $mData );
	}
	public function put( $data ){
		$this->db->update( "IELTS_TEST_ANSWER", $data, "SESSIONID = " . $data[ "SESSIONID" ] );
	}
}
?>