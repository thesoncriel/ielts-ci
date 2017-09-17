<? if (!defined('BASEPATH')) exit('No direct script access allowed'); 
include( BASEPATH . "../application/libraries/ielts.php" );

class Tddtest extends CI_Controller{
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
	
	public function timer($method = "start", $val = 10){
		
		if ($this->session->has_userdata("sessionTimer") == false){
			echo "no session data.<br/>";
			//$this->load->library("ielts/sessionTimer");
			//$this->session->set_userdata("sessionTimer", $this->sessiontimer);
			$object = new sessionTimer();
			$this->session->set_userdata("sessionTimer", $object);

		}
		else{
			$this->sessiontimer = $this->session->userdata("sessionTimer");
			$object = $this->sessiontimer; 
		}

		//$this->load->library("ielts/sessionTimer");
		//$timer = $this->sessiontimer;

		print_r($object);
		
		if ($method == "start"){
			echo "timer is started.";
			$object->start($val);
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
			$this->session->unset_userdata("sessionTimer");
			return;
		}
		
		echo "Timer is OK";
	}
}

?>