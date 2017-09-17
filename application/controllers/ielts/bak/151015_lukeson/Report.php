<? defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper("url_helper");
	}
	public function index(){
		$this->load->view("ielts/_header_report");
		$this->load->view("ielts/report");
		$this->load->view("ielts/_footer");
	}
}
?>