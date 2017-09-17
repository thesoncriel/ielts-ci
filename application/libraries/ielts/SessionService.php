<? if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once "IeltsAnswerFieldType.php";
include_once "SessionTimer.php";
include_once "TestStatus.php";

class SessionService extends SessionMapper implements ISessionSyncListener{
	const Key_SessionId = "sessionId";
	const Key_Module 	= "module";
	const Key_LAnswer	= "lAnswer";
	const Key_RAnswer	= "rAnswer";
	const Key_MaxQuest	= "maxQuestion";
	
	const QUESTION_COUNT = 40;
	
	private $id = 0;
	private $newId = 0;
	private $module = 0;
	private $answer = null;
	private $maxQuestion = 10;
	
	public $_timer = null;
	public $status = null;
	
	// TODO: 문제 개수가 가변적일 경우에 대비하여 옵션을 받아서 처리하는 기능 구현할 것.
	public function __construct($moduleMin = 1, $moduleMax = 5, $maxQuestion = 40){
		if (is_array($moduleMin)){
			$mConfig = $moduleMin;
			$moduleMin 		= (isset($mConfig[0]))? $mConfig[0] : 1;
			$moduleMax 		= (isset($mConfig[1]))? $mConfig[1] : 5;
			$maxQuestion 	= (isset($mConfig[2]))? $mConfig[2] : 40;
		}
		parent::__construct(
			get_class($this),
			array(
				$this::Key_Module 		=> $this->createModule($moduleMin, $moduleMax),
				$this::Key_SessionId	=> $this->createId(),
				$this::Key_LAnswer		=> array_pad( array(), $maxQuestion, "" ),
				$this::Key_RAnswer		=> array_pad( array(), $maxQuestion, "" ),
				$this::Key_MaxQuest		=> $maxQuestion
			)
		);
		
		$this->module 		= $this->get( $this::Key_Module );
		$this->id 			= $this->get( $this::Key_SessionId );
		$this->answer		= array();
		$this->answer["L"] 	= $this->get( $this::Key_LAnswer );
		$this->answer["R"] 	= $this->get( $this::Key_RAnswer );
		$this->maxQuestion	= $this->get( $this::Key_MaxQuest );
		
		$this->status 		= new TestStatus();
		$this->_timer 		= array(
				"L" => new SessionTimer("L"), 
				"R" => new SessionTimer("R"));
		
		$this->_timer["L"]->addListener( $this );
		$this->_timer["R"]->addListener( $this );
		$this->status->addListener( $this );
	}
	
	public function isNew(){
		$sessionId = (int)$this->get( $this::Key_SessionId );
		$isNew = $sessionId == $this->newId;
		
		if ($isNew){
			$this->onSessionChangedTrigger("isNew", $this::Key_SessionId, $sessionId);
		}
		
		return $isNew;
	}
	
	public function timer($sbj = "L"){
		return $this->_timer[ $sbj ];
	}
	
	protected function createId(){
		$this->newId = intval( microtime( TRUE ) * 100 );
		
		return $this->newId;
	}
	protected function createModule($min, $max){
		return rand( $min, $max );
	}
	
	public function setId($id){
		$this->put( $this::Key_SessionId, $id );
		$this->id = (int)$id;
	}
	
	public function setModule($moduleId){
		$this->put( $this::Key_Module, $moduleId );
		$this->module = (int)$moduleId;
	}
	
	public function setStatus($status){
		$this->status->setStatus($status);
	}
	
	public function setTimerCurr($sub, $limit, $curr){
		$timer = &$this->timer($sub);
		$timer->start($limit, $curr);
	}

	/* 세션에 데이터 넣는걸 일괄 처리 하고 싶었는데..
	 * 딱히 방법이 안떠오름..
	public function setData($data, $val = false){
		$this->put( "setData", $data, $val );
	}
	*/
	public function getId(){
		return $this->id;
	}
	public function getModule(){
		return $this->module;
	}
	public function getMaxQuestion(){
		return $this->maxQuestion;
	}
	public function setAnswer($type = "L", $data = false){
		$answer = &$this->answer;//배열 포인터 전달. 없으면 자꾸 key를 못찾음.
		$sField = $this->toFieldName( $type );
		$dataRes = null;
		
		if (($data == false) && ($data != "0")){
			$dataRes = $this->onDataRequestTrigger("setAnswer", $type);
			$data = $dataRes[0];
		}
		
		$answer[ $type ] = explode("|", $data);
		$this->put( $sField, $answer[ $type ] );
	}
	public function putAnswer($type = "L", $questionNum = 0, $value = "1"){
		$answer = &$this->answer;//배열 포인터 전달. 없으면 자꾸 key를 못찾음.
		$sField = $this->toFieldName( $type );
		$iQuestionIndex = 0;
		
		if (is_numeric($questionNum)){
			$iQuestionIndex = (int)$questionNum; // 들어오는 번호 값이 index 라 가정함. 
		}
		
		if ($iQuestionIndex < 0){
			$iQuestionIndex = 0;
		}
		
		if ( array_key_exists( $type, $answer ) == false ){
			//echo "answer key is not exists. <br/>";
			$answer[ $type ] = array_pad( array(), $this->maxQuestion, "" );
		}
		
		$answer[ $type ] = $this->checkAnormalyAnswerList( $answer[ $type ] );
		$answer[ $type ][ $iQuestionIndex ] = $value;
		
		$this->put( $sField, $answer[ $type ] );
		$this->onSessionChangedTrigger( "putAnswer", $sField, $answer[ $type ] );
	}
	public function getAnswer($type = "L", $questionNum = 1){
		try{
			$iQuestionIndex = 0;
		
			if (is_numeric($questionNum)){
				$iQuestionIndex = (int)$questionNum - 1;
			}
			
			if ($iQuestionIndex < 0){
				$iQuestionIndex = 0;
			}
			
			return $this->answer[ $type ][ $iQuestionIndex ];
		}
		catch(Exception $ex){
			return 0;
		}
	}
	/* 가끔 DB에서 데이터 불러와서 세션에 적용 시키면 배열길이가 의도치 않게 짧은 경우가 있다.
	 * 원인을 찾기 힘드므로 일단 요걸로 대체함
	 */
	public function checkAnormalyAnswerList(&$list){
		$iCnt = count( $list );
		$arr = null;
		
		if ($iCnt == $this->maxQuestion){
			return $list;
		}
		
		$arr = array_pad( array(), $this->maxQuestion, "" );
		
		foreach($list as $index => $item){
			$arr[ $index ] = $item;
		}
		
		return $arr;
	}
	public function getAnswerList($type = "L"){
		$list = $this->answer[ $type ];
		$list = $this->checkAnormalyAnswerList($list);
		$this->answer[ $type ] = $list;
		
		return $list;
	}
	public function serializeAnswer($type = "L"){
		try{
			return implode("|", $this->answer[$type]);
		}
		catch(Exception $ex){
			return null;
		}
	}
	
	// 사용자가 임의의 값을 넣게 되면 기본값을 취하도록 조치함
	public function toFieldName( &$type = "L"){
		switch($type){
			case "L":
				return $this::Key_LAnswer;
			case "R":
				return $this::Key_RAnswer;
			default:
				$type = "L";
				return $this::Key_LAnswer;
		}
	}
	
	//TODO: DB에 이미 데이터가 있을 경우 생성 후에 사용한다.
	public function apply($data){
		
	}
	
	public function onSessionChanged($className, $method, $key, $val){
		$this->onSessionChangedTrigger( $className . "." . $method, $key, $val );
	}
	public function onDataRequest($className, $method, $key, $conditional = false){
		$res = $this->onDataRequestTrigger( $className . "." . $method, $key, $conditional );
	}
}
?>