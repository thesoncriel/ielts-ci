<?
class IngList extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('list_model');
		$this->load->helper('url_helper');
	}
	public function index(){
		$data['list'] = $this->list_model->get_list();
		$data['title'] = 'New IELTS System !!';

		$this->load->view('templates/header', $data);
		$this->load->view('list', $data);
		$this->load->view('templates/footer', $data);
	}
	public function view($koreanname = null){
		$data['list_item'] = $this->list_model->get_list($koreanname);
		$data['title'] = 'New IELTS System !! ~~';

		$this->load->view('templates/header', $data);
		$this->load->view('view', $data);
		$this->load->view('templates/footer', $data);
	}

	public function test(){
		//echo 'test';
	}
}
?>