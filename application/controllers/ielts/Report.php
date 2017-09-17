<? defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper("url_helper");
		$this->load->library("session");// 이걸 안불러들이면 별도로 만든 Custom Library 의 Session 기능이 안먹힌다...
		$this->load->model("ielts/question");
		$this->load->model("ielts/answer");
		$this->load->library("ielts/sessionService", array(1,5,40), "service");
	}
	public function index(){
		$answer = $this->answer->load($this->service->getId());
		$band = $this->calcScore();
		//echo $this->service->getId() . "========";
		$data["testdate"] = date_format(date_create($answer["TESTDATE"]), "Y-m-d");
		$data["l_score"] = $band["L"]["sum"];
		$data["r_score"] = $band["R"]["sum"];
		$data["l_band"] = $band["L"]["band"];
		$data["r_band"] = $band["R"]["band"];
		//$data["allband"] = ($band["L"]["band"] + $band["R"]["band"]) / 2;
		
		//print_r($band);
		
		$this->service->status->end("L");
		$this->service->status->end("R");
		$this->service->status->end();
		$this->service->timer("L")->stop();
		$this->service->timer("R")->stop();
		
		$this->answer->put( array(
			"SESSIONID" => $this->service->getId(),
			"L_SCORE" => $band["L"]["sum"],
			"R_SCORE" => $band["R"]["sum"],
			"L_BAND" => $band["L"]["band"],
			"R_BAND" => $band["R"]["band"]
		) );
		
		$this->load->view("ielts/_header_report");
		$this->load->view("ielts/report", $data);
		$this->load->view("ielts/_footer");
	}
	
	public function calcScore(){
		$module = $this->service->getModule();
		$answerL = $this->service->getAnswerList("L");
		$answerR = $this->service->getAnswerList("R");
		$scoreL = $this->question->batchCorrect("G", $module, "L", $answerL);
		$scoreR = $this->question->batchCorrect("G", $module, "R", $answerR);
		
		return array(
			"L" => $scoreL,
			"R" => $scoreR
		);
	}
}
?>