<? if (!defined('BASEPATH')) exit('No direct script access allowed'); 
include BASEPATH . "../application/libraries/ielts/ISessionSyncListener.php";

class Tdd extends CI_Controller implements ISessionSyncListener{
	public function __construct(){
		parent::__construct();
		
		$this->load->library("session");
	}
	
	public function model($method = "save", $val = 0){
		$this->load->model( "ielts/answer" );
		$row = array(
			"SESSIONID" => $val
		);
		
		if ($method == "save"){
			$this->answer->save( $row );
		}
		elseif ($method == "put"){
			$row["R_SEC"] = 300;
			$this->answer->put( $row );
		}
		elseif($method == "load"){
			$result = $this->answer->load( $row["SESSIONID"] );
			echo print_r($result) . "<br/><br/>";
			
			if (isset($result->R_SEC)){
				echo $result->R_SEC . "<br/>";
			}
			
		}
		
		echo "Model is OK";
	}
	
	public function modelq($moduleNum = 1, $testType = "L"){
		$this->load->model( "ielts/question" );
		print_r($this->question->search( "G", $moduleNum, $testType ));
		echo "Question Model is OK";
	}
	
	public function timer($method = "start", $val = 10){
		$this->load->library("ielts/sessionTimer");
		$timer = $this->sessiontimer;
		//$timer = new SessionTimer($this->session);
		//$this->sessiontimer = $timer;
		
		if ($method == "start"){
			echo "timer is started.";
			$timer->start($val);
		} 
		else if($method == "stop"){
			$timer->stop();
		}
		else if($method == "getLimit"){
			echo "limit: " . $timer->getLimit();
		}
		else if($method == "getCurrent"){
			echo "current: " . $timer->getCurrent();
		}
		else if($method == "hasTimeOut"){
			echo "timeOut?: " . $timer->hasTimeOut();
		}
		else if($method == "clear"){
			$timer->clear();
			
			echo "session is cleared.";
			
			return;
		}
		else if($method == "isStop"){
			echo $timer->isStop();
		}
		
		echo "<br/><br/>" . print_r($timer);
		
		echo "Timer is OK";
	}
	
	public function status($method = "", $val = 0){
		$this->load->library("ielts/testStatus");
		$status = $this->ieltsteststatus;
		
		if ($method == "start"){
			if ($val === 0){
				$status->start();
			}
			else{
				$status->start($val);
			}
		}
		else if($method == "end"){
			if ($val === 0){
				$status->end();
			}
			else{
				$status->end($val);
			}
		}
		else if($method == "getCurrent"){
			echo "current: " . $status->getCurrent() . "<br/>";
		}
		else if($method == "getStatus"){
			echo "status: " . $status->getStatus() . "<br/>";
		}
		else if($method == "toSubjectCode"){
			echo "subject: " . $status->toSubjectCode($val) . "<br/>";
		}
		else if($method == "hasStart"){
			echo "is started?: " . $status->hasStart($val) . "<br/>";
		}
		else if($method == "hasEnd"){
			echo "is ended?: " . $status->hasEnd($val) . "<br/>";
		}
		else if($method == "clear"){
			$status->clear();
			
			echo "session is cleared.";
			
			return;
		}
		
		echo $method . " : running. <br/>";
		echo "Status is OK.";
	}

	public function service($method = "", $val = false, $val2 = false){
		$this->load->library("ielts/sessionService");
		$service = $this->sessionservice;
		$service->addListener($this);
		
		if ($method == "isNew"){
			echo "isNew: ";
			if ($service->isNew()){
				echo "yes. it's new session! - ";
			}
			else{
				echo "no. already exists. - ";
			}
			echo $service->getId();
		}
		else if ($method == "answer"){
			$service->putAnswer("R", (int)$val, $val2);
		}
		else if ($method == "getAnswer"){
			echo $service->getAnswer("R", (int)$val);
		}
		else if ($method == "setAnswer"){
			if ($val){
				$service->setAnswer("R", str_replace("-", "|", $val) );
			}
			else{
				$service->setAnswer("R");
			}
		}
		
		echo "<div>";
		echo $method . " : running. <br/>";
		echo "</div>";
	}
	
	public function session($clear = false){
		if ($clear == "clear"){
			session_unset();
			echo "session has cleared.";
		}
		else{
			print_r($_SESSION);
		}
	}
	
	public function onSessionChanged($className, $method, $key, $val){
		echo "<h2>";
		echo get_class($this);
		echo "</h2>";
		echo "TDD::onSessionChanged : <br/>";
		echo $className . "<br/>";
		echo $method . "<br/>";
		echo $key . "<br/>";
		echo print_r($val) . "<hr/>";
	}
	public function onDataRequest($className, $method, $key, $conditional = false){
		echo "<h2>";
		echo get_class($this);
		echo "</h2>";
		echo "TDD::onDataRequest : <br/>";
		echo $className . "<br/>";
		echo $method . "<br/>";
		echo $key . "<br/>";
		echo "<hr/>";
		
		return null;
	}
}
?>