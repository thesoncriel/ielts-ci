<? defined('BASEPATH') OR exit('No direct script access allowed');

include "SubjectTestController.php";

class Reading extends SubjectTestController{
	public function __construct(){
		parent::__construct();
		$this->subjectCode = "R";
		$this->subject = "reading";
		$this->timeLimit = 60 * 60; // Reading은 30분
	}
}
?>