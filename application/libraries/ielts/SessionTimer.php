<? if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once ("SessionMapper.php");
class SessionTimer extends SessionMapper{
	const Key_Limit 		= "limit";
	const Key_NowChecking 	= "nowChecking";
	const Key_FirstTime		= "firstTime";
	const key_Current		= "current";
	
	private $prefix = "";
	private $limit = 0;
	private $nowChecking = false;
	private $firstTime = 0;
	private $current = 0;
	
	public function __construct(
		$prefix = "",
		$limit = 0, 
		$nowChecking = false, 
		$firstTime = 0,
		$current = 0
	){
		parent::__construct( 
			"SessionTimer",
			array(
				$prefix.$this::Key_Limit		=> $limit,
				$prefix.$this::Key_NowChecking 	=> $nowChecking,
				$prefix.$this::Key_FirstTime	=> $firstTime,
				$prefix.$this::key_Current		=> $current
			)
		);
		
		$mData = null;
		
		$this->prefix		= $prefix;
		$this->firstTime 	= (int)$this->get( $prefix.$this::Key_FirstTime, 0);
		$this->nowChecking 	= (bool)$this->get( $prefix.$this::Key_NowChecking, false);
		$this->limit 		= (int)$this->get( $prefix.$this::Key_Limit );
		$this->current 		= $this->calcCurrent();
		
		if ($this->current >= $this->limit){
			$this->stop();
		}
		else{
			$mData = array(
				$prefix.$this::key_Current	=> $this->current
			);
			$this->put( $mData );
			$this->onSessionChangedTrigger( "__construct", $mData );
		}
	}
	public function calcCurrent(){
		$prefix = $this->prefix;
		$iFirst = $this->getFirstTime();
		$iCurrent = time() - $iFirst;
		$iLimit = $this->getLimit();
		
		if ($this->isStop()){
			return (int)$this->get( $prefix.$this::key_Current );
		}
		
		if ($iCurrent >= $iLimit){
			return $iLimit;
		}
		
		return $iCurrent;
	}
	
	public function start($limit = 60, $curr = 0){
		$prefix = $this->prefix;
		$this->limit = (int)$limit;
		$this->current = (int)$curr;
		$this->nowChecking = true;
		$this->firstTime = time() - $this->current;
		$mData = array(
			$prefix.$this::Key_Limit		=> $this->limit,
			$prefix.$this::Key_NowChecking 	=> $this->nowChecking,
			$prefix.$this::Key_FirstTime	=> $this->firstTime,
			$prefix.$this::key_Current		=> $this->current
		);
		
		$this->put( $mData );
		$this->onSessionChangedTrigger( "start", $mData );
	}
	public function stop(){
		$prefix = $this->prefix;
		$mData = array(
			$prefix.$this::Key_NowChecking 	=> false,
			$prefix.$this::key_Current		=> $this->current
		);
		$this->put( $mData );
		$this->onSessionChangedTrigger( "stop", $mData );
		$this->nowChecking = false;
	}
	public function getLimit(){
		return $this->limit;
	}
	public function isStop(){
		return $this->nowChecking == false;
	}
	public function getFirstTime(){
		return $this->firstTime;
	}
	public function getCurrent($checkStop = false){
		if ($checkStop){
			return ($this->isStop())? $this->limit : $this->current;
		}
		
		return $this->current;
	}
	// getCurrent의 short-cut.
	public function curr(){
		return $this->getCurrent();
	}
	public function hasTimeOut(){
		$bTimeOut = $this->getCurrent() >= $this->getLimit();
		
		return $bTimeOut;
	}
}
?>