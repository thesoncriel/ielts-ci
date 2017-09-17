<? if (!defined('BASEPATH')) exit('No direct script access allowed');
class SessionTimer{
	private $limit = 0;
	private $nowChecking = false;
	private $firstTime = 0;
	
	public function start($limit = 60){
		$this->limit = (int)$limit;
		$this->nowChecking = true;
		$this->firstTime = time();
	}
	public function stop(){
		$this->nowChecking = false;
	}
	public function getLimit(){
		return $this->limit;
	}
	public function getCurrent(){
		$iFirst = $this->firstTime;
		$iCurrent = time() - $iFirst;
		
		if ($iCurrent >= $this->getLimit()){
			return $this->getLimit();
		}
		
		return $iCurrent;
	}
	public function hasTimeOut(){
		$bTimeOut = $this->getCurrent() >= $this->getLimit(); 
		
		return $bTimeOut;
	}
}
?>