<? defined('BASEPATH') OR exit('No direct script access allowed');
class Reading extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper("url_helper");
	}
	public function index(){
		$data["answer_sheet"] = array();
		
		for($i = 0; $i < 30; $i++){
			array_push($data["answer_sheet"], array("A", "B", "C"));
		}
		
		$this->load->view("ielts/_header");
		$this->load->view("ielts/reading", $data);
		$this->load->view("ielts/_footer");
	}
}
?>