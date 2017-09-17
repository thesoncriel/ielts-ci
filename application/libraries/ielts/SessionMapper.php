<?
include_once "ISessionSyncListener.php";

class SessionMapper{
	private $className = "";
	private $arrKeyDef = null;
	protected $listener = null;
	protected $autoEventTrigger = false;
	
	public function __construct($className, $arrKeyDef, $notFoundToExcept = false){
		foreach($arrKeyDef as $key => $def){
			if (isset($_SESSION[ $className . "." . $key ]) == false){
				if ($notFoundToExcept){
					throw new Exception("There is no key name in session.", 1);
					
				}
				$_SESSION[ $className . "." . $key ] = $def;
			}
		}
		
		$this->listener = array();
		$this->className = $className;
		$this->arrKeyDef = $arrKeyDef;
	}
	
	protected function put($key, $val = false){
		//$ci = &get_instance();
		//$session = $ci->load->library("session");
		
		if (is_array($key)){
			foreach($key as $name => $value){
				$_SESSION[ $this->className . "." . $name ] = $value;
			}
		}
		else{
			$_SESSION[ $this->className . "." . $key ] = $val;
		}
		
		if ($this->autoEventTrigger){
			$this->onSessionChangedTrigger("", $key, $val);
		}
	}
	protected function get($key, $def = ""){
		try{
			return $_SESSION[ $this->className . "." . $key ];
		}
		catch(Exception $ex){
			return $def;
		}
	}
	
	public function clear(){
		$className = $this->className; 
		$arrKey = $this->arrKey;
		
		foreach($this->arrKeyDef as $key => $def){
			unset( $_SESSION[ $className . "." . $key ] );
		}
	}
	
	public function addListener($listener){
		$this->listener[] = $listener;
		
		return $this;
	}
	
	// TODO: 이벤트 삭제 기능이 필요해 지면 구현할 것
	public function removeSessionChangedListener($listener){
		//array_diff ($this->sessionChangedListener
	}
	
	protected function onSessionChangedTrigger($method, $key, $val = false){
		try{
			foreach($this->listener as $listener){
				$listener->onSessionChanged($this->className, $method, $key, $val);
			}
		}
		catch(Exception $ex){
			
		}
	}
	protected function onDataRequestTrigger($method, $key, $conditional = false){
		$aRet = array();
		$tmp = null;
		
		try{
			foreach($this->listener as $listener){
				$tmp = $listener->onDataRequest($this->className, $method, $key, $conditional);
				
				if (is_null($tmp) == false){
					$aRet[] = $tmp;
				}
			}
		}
		catch(Exception $ex){
			
		}
		
		return $aRet;
	}
	
	public function __destruct(){
		unset($this->listener);
	}
}
?>