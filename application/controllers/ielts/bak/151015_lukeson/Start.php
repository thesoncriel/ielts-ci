<? defined('BASEPATH') OR exit('No direct script access allowed');

include( BASEPATH . "../application/libraries/ielts.php" );

class Start extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper("url_helper");
		$this->load->library("session");
		
		$timer = new SessionTimer();
	}
	public function index($subject = "L"){
		if ($this->input->post("subject")){
			$data["subject"] = $this->input->post("subject");
		}
		else{
			$data["subject"] = $subject;
		}
		//TODO: subject에 따른 예외처리 할 것. 이유: 걍 무턱대고 파라메터 넣으면...
		$this->load->view("ielts/_header");
		$this->load->view("ielts/start", $data);
		$this->load->view("ielts/_footer");
	}
}
?>