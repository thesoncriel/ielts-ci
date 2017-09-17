<?if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once ("SessionMapper.php");
class TestStatus extends SessionMapper{
	const Key_Status	= "status";
	const Key_Current	= "current";
	
	const Listening = 1;
	const Reading = 4;
	const Speaking = 16;
	const Writing = 64;
	const End = 2048;
	
	private $current = 0; // 현재 진행되는 과목 코드만 가짐.
	private $status = 0;  // 실제 DB에 저장될 데이터. 비트연산으로 축적 되어 있음.
	
	public function __construct(){
		parent::__construct(
			"TestStatus",
			array(
				$this::Key_Status	=> 0,
				$this::Key_Current	=> 0
			)
		);
		
		$this->current = (int)$this->get("current");
		$this->status = (int)$this->get("status");
		
		//echo $this->status . ":::::";
	}
	
	public function start($type = TestStatus::Listening){
		$iType = $this->toSubjectCode( $type, true );
		$mData = null;
		
		$this->status = $this->status | $iType;
		$this->current = $iType;
		$mData = array(
			$this::Key_Status	=> $this->status,
			$this::Key_Current	=> $this->current
		);
		
		$this->put( $mData );
		$this->onSessionChangedTrigger( "start", $mData );
	}
	public function end($type = TestStatus::End){
		$iType = $this->toSubjectCode( $type, true );
		$mData = null;
		
		if ($iType != TestStatus::End){
			$this->status |= ( $iType << 1 );
		}
		else{
			$this->status |= $iType;
			$this->current = $iType;
		}
		
		$mData = array(
			$this::Key_Status	=> $this->status,
			$this::Key_Current	=> $this->current
		);
		
		$this->put( $mData );
		$this->onSessionChangedTrigger( "end", $mData );
	}
	public function getCurrent($isNumeric = true){
		if ($isNumeric){
			return $this->current;
		}
		else{
			return $this->toSubjectCode();
		}
	}
	public function getStatus(){
		return $this->status;
	}
	public function setStatus($val){
		$this->status = (int)$val;
		$this->current = $this->getCurrentByStatus();
	}
	
	public function getCurrentByStatus($status = false){
		$aTestSeq = $this->getTestSeq();
		$status = ($status)? $status : $this->status;
		$iCode = "";
		$iCurrent = 0;
		
		foreach($aTestSeq as $index => $seq){
			$iCode = $this->toSubjectCode($seq, true);
			if ( ($status & $iCode) > 0 ){
				$iCurrent = $iCode;
			}
		}
		
		return $iCurrent;
	}
	public function getNext(){
		$aTestSeq = $this->getTestSeq();
		$sCurr = $this->getCurrent();
		$index = array_search($sCurr, $aTestSeq);
		
		return $aTestSeq[ $index ];
	}
	public function toSubjectCode($code = false, $numericOnly = false){
		if ($code == $this::End){
			return $this::End;
		}
		
		if ($code == false){
			
			return $this->toAlphaCode( $this->current );
		}
		
		if ($numericOnly == true){
			if (is_numeric($code)){
				return (int)$code;
			}
			
			return $this->toNumberCode( $code ); 
		}
		
		
		if (is_numeric($code)){
			return $this->toAlphaCode( (int)$code );
		}
		
		return $this->toNumberCode( $code );
	}
	protected function toAlphaCode($code){
		switch( $code ){
			case $this::Listening:
				return "L";
			case $this::Reading:
				return "R";
			case $this::Speaking:
				return "S";
			case $this::Writing:
				return "W";
			case $this::End:
				return "E";
			default:
				return "";
		}
	}
	protected function toNumberCode($code){
		switch( strtoupper( $code ) ){
			case "L":
				return $this::Listening;
			case "R":
				return $this::Reading;
			case "S":
				return $this::Speaking;
			case "W":
				return $this::Writing;
			case "E":
				return $this::End;
			default:
				return 0;
		}
	}
	
	public function isMyTestSeq($type){
		$iType = $this->toSubjectCode($type, true);
	}
	
	public function hasStart($type = 0){
		$iType = $this->toSubjectCode( $type, true );
		
		if ($iType == 0){
			return $this->status > 0;
		}
		
		return ( $this->status & $iType ) > 0;
	}
	public function hasEnd($type = TestStatus::End){
		$iType = $this->toSubjectCode( $type, true );

		if ($iType != TestStatus::End){//echo $this->status . "::dd:";
			return ( $this->status & ( $iType << 1 ) ) > 0;
		}
		
		return ( $this->status & $iType ) > 0;
	}
	
	
	// TODO: 요걸 따로 빼든지 아니면 본 클래스를 상속받아 Override 하던지 결정 내려야 할 듯
	public function getTestSeq(){
		return array("L", "R", "E");
	}
}
?>