<? defined('BASEPATH') OR exit('No direct script access allowed');

include BASEPATH . "../application/libraries/ielts/ISessionSyncListener.php";

class SubjectTestController extends CI_Controller implements ISessionSyncListener{
	//protected $subjectCode = "";
	
	protected $subjectCode = "L";
	protected $subject = "listening";
	protected $timeLimit = 600;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper("url_helper");
		$this->load->library("session");// 이걸 안불러들이면 별도로 만든 Custom Library 의 Session 기능이 안먹힌다...
		$this->load->model( "ielts/question" );
		$this->load->model( "ielts/answer" );
		$this->load->library("ielts/sessionService", array(1,5,40), "service");
		
		$this->service->addListener($this);
	}
	
	public function index(){
		$sbj = $this->subjectCode;
		$service = $this->service;
		$list = null;
		
		if ($this->checkSessionId() == false){
			
		}
		
		$list = $this->question->search("G", $this->service->getModule(), $sbj);
		//print_r($list);
		//print_r($this->service);
		
		
		
		/*
		if ($service->status->getCurrent() == ""){
			//$service->timer($sbj)->start(60 * 60);
		}
		else if($service->status->getCurrent() != "L"){
			
		}
		else if($service->timer($sbj)->hasTimeOut()){
			
		}
		*/
		
		if ($service->status->hasStart( $sbj ) == false){
			//echo $sbj . ": new Start"; 
			$service->status->start( $sbj );
			$service->timer($sbj)->start( $this->timeLimit, 0);
		}
		
		if ($service->timer($sbj)->hasTimeOut()){
			//$this->next();
			//return;
		}
		if 	($service->status->hasStart($sbj) &&
			($service->status->hasEnd($sbj) == false) ){
			
		}
			
		$data["answer_sheet"] = $list;
		$data["answer_list"] = $service->getAnswerList($sbj);
		$data["moduletype"] = "G";
		$data["module"] = $service->getModule();
		$data["timeout"] = $service->timer($sbj)->hasTimeOut();
		$data["timercurr"] = $service->timer($sbj)->getCurrent(true);
		$data["timerlimit"] = $service->timer($sbj)->getLimit();
		$data["status_current"] = $service->status->getCurrent(false);// 기본적으로 true이며 이 때는 숫자 코드를 내 놓는다.
		
		//print_r($data["answer_list"]);
		//echo "status_current: ".  $data["status_current"]; 
		
		$this->applyView($this->subject, $data);
	}
	public function answer(){
		$sbj = $this->subjectCode;
		$num = $this->input->post("num");
		$val = $this->input->post("val");
		
		/* // TODO: 원래 과목 시작여부 & 종료 여부 및 타임아웃 여부 확인 후에 수행 해야 한다...
		if (($service->status->hasStart("L") == true) &&
			($service->status->hasEnd("L") == false) && 
			($this->service->timer($sbj)->hasTimeOut() == false)){
			
		}
		*/
		if (($num != "") && ($val != "")){
			$this->service->putAnswer($sbj, $num, $val);
		}
	}
	
	// 시험 종료 시 submit으로 한번에 답지를 보냈을 때 처리하려 했던 것
	public function collectAnswer(){
		$val = "";
		$max = $this->service->getMaxQuestion();
		$answer = array_pad( array(), $max, "" );
		
		for($i = 1; $i < $max; $i++){
			
		}
	}
	
	// FIXME: 요거 잘 안되고 있음. 수정 할 것.
	protected function next(){
		$sNext = $this->service->status->getNext();
		$sSubject = "";
		
		switch($sNext){
			case "L":
				$sSubject = "listening";
				break;
			case "R":
				$sSubject = "reading";
				break;
			case "E":
				$sSubject = "report";
				break;
		}
		
		redirect("/ielts/$sSubject");
	} 
	
	// true면 요청된 세션 id가 DB에 있다는 것. false면 없음.
	protected function checkSessionId(){
		$sbj = $this->subjectCode;
		$row = null;
		$currStatus = "";
		$currSec = 0;
		$sessionId = $this->input->post("sessionid");
		
		if ($sessionId && (is_numeric($sessionId) == false)){
			redirect("/ielts/start/invalid");
			exit;
		}
		
		if ($sessionId && ($this->service->getId() != $sessionId)){
			$row = $this->onDataRequest("IeltsTest", "index", "sessionid", $this->input->post("sessionid") );
			//print_r($row);
			
			if (isset($row["SESSIONID"]) == false){
				redirect("/ielts/start/invalid");
				exit;
			}
			
			$this->service->setId( $row["SESSIONID"] );
			$this->service->setModule( $row["MODULE_NUM"] );
			$this->service->setStatus( $row["STATUS"] );
			$this->service->setAnswer( "L", $row["L_ANSWER"] );
			$this->service->setAnswer( "R", $row["R_ANSWER"] );
			
			$currStatus = $this->service->status->getCurrent(false);
			$currSec = ($currStatus == "L")? $row["L_SEC"] : $row["R_SEC"];
			
			$this->service->timer($sbj)->start( $this->timeLimit, $currSec );
			// TODO: 해당 과목의 시간 지남 여부를 체크할 필요가 있음.
			
			return true;
		}
		else if ($this->service->isNew()){
			// TODO: 추후에 별도로 ID 발급시, 혹은 B2B 별도 업무 처리 시 필요할 것임.
			//redirect("/ielts/start");
			//$this->onSessionChanged($className, $method, $key, $val)
		}
		
		return false;
	}
	
	protected function applySessionData(&$data){
		$this->service->status->setStatus( $data["STATUS"] );
	}
	
	protected function applyView($name = false, &$data){
		$name = ($name)? $name : $this->subject;
		$this->load->view("ielts/_header");
		$this->load->view("ielts/$name", $data);
		$this->load->view("ielts/_footer");
	}
	
	public function onSessionChanged($className, $method, $key, $val){
		switch($method){
			case "putAnswer":
				$this->_putAnswer();
				break;
			case "isNew":
				$this->_isNew($val);
				break;
		}
	}
	
	protected function _isNew($val){
		$this->answer->save(array(
			"USERID" 		=> "ielts_temp",
			"USERIP" 		=> $_SERVER['REMOTE_ADDR'],
			"SESSIONID"		=> $val,
			"MODULE_NUM"	=> $this->service->getModule()
		));
	}
	
	protected function _putAnswer(){
		$sbj = $this->subjectCode;
		$this->answer->put( 
		array(
			"SESSIONID" => $this->service->getId(),
			$sbj."_ANSWER" => $this->service->serializeAnswer($sbj)
		) );
	}
	
	public function onDataRequest($className, $method, $key, $conditional = false){
		switch($className . "." . $method){
			case "IeltsTest.index":
				return $this->answer->load($conditional);
		}
	}
}
?>