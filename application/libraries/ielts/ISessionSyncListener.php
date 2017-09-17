<?
// 하나의 클래스는 하나의 Model만을 관여하도록 설계하자...
interface ISessionSyncListener{
	public function onSessionChanged($className, $method, $key, $val);
	public function onDataRequest($className, $method, $key, $conditional = false);
}
?>