<?
class Question extends CI_Model{
	public function __construct(){
		parent::__construct();

		$this->load->database();
	}

	public function search($moduleType = "G", $moduleNum = 1, $testType = "L"){
		$query = $this->db->order_by("QNUM", "ASC")->get_where( 
			"IE_QUESTION", 
			array( 
				"MODULE_TYPE" => $moduleType,
				"MODULE_NUM" => $moduleNum,
				"TEST_TYPE" => $testType ) );
		
		$list = $query->result_array();
		$list = $this->applyAnswerLabel($list);
		
		return $list;
	}

	public function applyAnswerLabel(&$list){
		$aNum	= range("1", "10");
		$aAlpha = range("A", "J");
		$aRoman = array(
			"i", "ii", "iii", "iv", "v", "vi", "vii", "viii", "ix", "x"
			//"I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"
			);
		$aLabel = null;
		
		foreach($list as $index => $item){
			switch($item["A_TYPE"]){
				case 1:
					$aLabel = array_slice($aNum, 0, $item["A_NUM"]);
					break;
				case 2:
					$aLabel = array_slice($aRoman, 0, $item["A_NUM"]);
					break;
				case 3:
					$aLabel = array_slice($aAlpha, 0, $item["A_NUM"]);
					break;
				default:
					$aLabel = array();
			}
			
			//echo $index . "<br/>";
			$list[ $index ][ "A_LABEL" ] = $aLabel;
			
			//print_r($aLabel);
		}
		
		//print_r($list);
		
		return $list;
	}
	
	public function batchCorrect($moduleType = "G", $moduleNum = 1, $testType = "L", &$answer){
		$aQuest = $this->search($moduleType, $moduleNum, $testType);
		$iCorrect = 0;
		$iPos = 1;
		$iSum = 0;
		$isCorrect = false;
		$sTmp = "";
		$sAsw = "";
		$aResult = array();
		$nBand = 0;
		
		//echo "<h3>";
		//echo $moduleType . ", " . $moduleNum . ", " . $testType . ", ";
		//echo "</h3>";
		
		foreach($aQuest as $index => $q){
			$isCorrect = false;
			
			//echo $answer[ $index ] . " : " . $q["CORRECT"] . "<br/>";
			
			if ($answer[ $index ] == ""){
				
			}
			else if ($q["Q_TYPE"] == "S"){
				if ($answer[ $index ] == $q["CORRECT"]){
					$isCorrect = true;
				}
			}
			else{
				$sAsw = strtolower( $answer[ $index ] );
				$sTmp = strtolower( $q["CORRECT"] );
				
				//echo "asw: $sAsw === tmp: $sTmp <br/>";
				
				if (strstr( $sTmp, $sAsw ) != false){
					$isCorrect = true;
					//echo "OK???????????<br/>";
				}
			}
			
			if ($isCorrect == true){
				$iCorrect = $iCorrect | (1 << $index);
				$iSum++;
				$aResult[] = 1;
			}
			else{
				$aResult[] = 0;
			}
		}
		
		return array(
			"result" => $aResult,
			"bitData" => $iCorrect,
			"sum" => $iSum,
			"band" => $this->getBand($moduleType, $testType, $iSum)
		);
	}
	
	public function getBand($moduleType = "G", $testType = "L", $sum){
		if ($testType == "L"){
			return $this->_bandConvertL($sum);
		}
		if ($moduleType == "G" && $testType == "R"){
			return $this->_bandConvertGR($sum);
		}
	}
	
	protected function _bandConvertL($sum){
		switch($sum){
			case 1:
				return 1;
			case 2:
				return 2;
			case 3:
				return 2.5;
			case 4:
			case 5:
			case 6:
				return 3;
			case 7:
			case 8:
			case 9:
				return 3.5;
			case 10:
			case 11:
			case 12:
			case 13:
				return 4;
			case 14:
			case 15:
			case 16:
				return 4.5;
			case 17:
			case 18:
			case 19:
			case 20:
				return 5;
			case 21:
			case 22:
			case 23:
			case 24:
				return 5.5;
			case 25:
			case 26:
			case 27:
			case 28:
				return 6;
			case 29:
			case 30:
			case 31:
			case 32:
				return 6.5;
			case 33:
			case 34:
			case 35:
				return 7;
			case 36:
			case 37:
				return 7.5;
			case 38:
				return 8;
			case 39:
				return 8.5;
			case 40:
				return 9;
			default:
				return 0;
		}
	}
	
	protected function _bandConvertGR($sum){
		switch($sum){
			case 1:
			case 2:
				return 1;
			case 3:
				return 2;
			case 4:
				return 2.5;
			case 5:
			case 6:
			case 7:
			case 8:
				return 3;
			case 9:
			case 10:
			case 11:
				return 3.5;
			case 12:
			case 13:
			case 14:
				return 4;
			case 15:
			case 16:
			case 17:
				return 4.5;
			case 18:
			case 19:
			case 20:
			//case 21:
				return 5;
			case 21:
			case 22:
			case 23:
			case 24:
			case 25:
				return 5.5;
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
				return 6;
			
			case 31:
			case 32:
			case 33:
			case 34:
				return 6.5;
			case 35:
			case 36:
				return 7;
			case 37:
				return 7.5;
			case 38:
				return 8;
			case 39:
				return 8.5;
			case 40:
				return 9;
			default:
				return 0;
		}
	}
	
	/* 데이터의 예외 사항을 처리하여 데이터가 전체적으로 균형있도록 만든다.
	 * 이런 내용들은 DB에 이미 기록되어 있고, 잘 못하면 전체 시스템에 영향을 줄 수 있으므로
	 * 새로운 시스템으로 전면 개편 될 때 까지는 이렇게 Model 부에서 별도로 처리 해 준다.
	 */
	public function except(&$item){
		
	}
	/*
	 * SQL GATE 에서 복사-붙여넣기 하고 array key-value로 바꿀 때
	 * notepad++ regex 사용기준
	 * ,|$
	 * " => "",\r\n
	 */
	/*public function save( $data ){
		$query = $this->db->query( "SELECT IELTS_TEST_ANSWER_SEQ.NEXTVAL AS ID FROM DUAL" );
		$row = $query->row();
		$id = $row->ID;
		
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
			 *//*
			"TESTDATE" 		=> date( "Y-m-d H:i:s" ),
			"STARTDATE" 	=> date( "Y-m-d H:i:s" ),
			"ENDDATE" 		=> date( "Y-m-d H:i:s" )
		);
		
		$mData = array_merge( $mDef, $data );
		
		$this->db->insert( "IELTS_TEST_ANSWER", $mData );
	}
	public function put( $data ){
		$this->db->update( "IELTS_TEST_ANSWER", $data, "SESSIONID = " . $data[ "SESSIONID" ] );
	}*/
}
?>