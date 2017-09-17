<? defined('BASEPATH') OR exit('No direct script access allowed');
include BASEPATH . "../application/libraries/ielts/ISessionSyncListener.php";

class Start extends CI_Controller implements ISessionSyncListener{
	public function __construct(){
		parent::__construct();

		$isNeedContinue = false;
		
		$this->load->helper("url_helper");
		$this->load->model("ielts/answer");
		$this->load->model("ielts/question");
		$this->load->library("session");
		$this->load->library("ielts/sessionService", array(1,5,40), "service");
		$this->service->addListener($this);
		
		if ($isNeedContinue){
			$this->doContinue();
		}
	}
	public function index($subject = "L"){
		$type = "";
		
		if ($subject == "invalid"){
			$data["invalid"] = true;
			$subject = "L";
		}
		else{
			$data["invalid"] = false;
		}
			
		if ($this->input->post("subject")){
			$subject = $this->input->post("subject");
		}
		if ($this->input->post("type")){
			$type = $this->input->post("type");
		}
		
		if ($this->session->has_userdata("l_refresh")){
			//$subject = "R";
			$data["l_refresh"] = true;
		}
		else{
			$data["l_refresh"] = false;
		}
		
		$data["subject"] = $subject;
		
		if ($this->input->post("do_this") == "restart"){
			session_unset();
			redirect("/ielts/start");
			exit;
		}
		
		if ($this->service->isNew()){
			//$this->onSessionChanged("Start", "index", "sessionid", $this->service->getId());
		}
		
		
		
		$data["status_current"] = $this->service->status->getCurrent(false);
		$data["sessionid"] = $this->service->getId();
		
		if ($this->input->post("do_this") == "end"){
			$this->service->timer($type)->stop();
			//$this->service->status->end("L");
		}
		//print_r($data);
		//TODO: subject에 따른 예외처리 할 것. 이유: 걍 무턱대고 파라메터 넣으면...
		$this->load->view("ielts/_header");
		$this->load->view("ielts/start", $data);
		$this->load->view("ielts/_footer");
	}
	protected function doContinue(){
		
	}
	
	public function onSessionChanged($className, $method, $key, $val){
		$this->answer->save(array(
			"USERID" 		=> "ielts_temp",
			"USERIP" 		=> $_SERVER['REMOTE_ADDR'],
			"SESSIONID"		=> $val,
			"MODULE_NUM"	=> $this->service->getModule()
		));
	}
	public function onDataRequest($className, $method, $key, $conditional = false){
		
	}
}
?>