<? defined('BASEPATH') OR exit('No direct script access allowed');

include "SubjectTestController.php";

class Listening extends SubjectTestController{
	//protected $subjectCode = "";
	
	public function __construct(){
		parent::__construct();
		
		$this->subject = "listening";
		$this->subjectCode = "L";
		$this->timeLimit = 60 * 30; // Listening은 30분
	}
	
	public function index(){
		if (($this->input->post("valid_msg") != "1") && 
			$this->session->has_userdata("l_aleady"))
		{
			$this->session->set_userdata("l_refresh", "1");
			redirect("/ielts/start/R");
			return;
		}
		else{
			$this->session->set_userdata("l_aleady", "1");
		}
		
		parent::index();
	}
}
?>