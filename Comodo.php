<?php
/**
* Clase Comodo
*/
class Comodo{

	private $conn;
	private $value='';
	private $key='';
	private $credencial=array();
	
	function __construct($host, $user, $pass, $db, $port='', $socket=''){
		if (!extension_loaded('mysqli'))trigger_error('Error: mysqli extension is not loaded', E_USER_ERROR);
		$this->credencial = array(
			$host,
			$user, 
			$pass,
			$db,
			$port == '' ? ini_get('mysqli.default_port') : $port,
			$socket == ''? ini_get('mysqli.default_socket') : $socket,
		);
	}

	public function connect(){
		$this->conn = new mysqli(
			$this->credencial[0],
			$this->credencial[1],
			$this->credencial[2],
			$this->credencial[3],
			$this->credencial[4],
			$this->credencial[5]
		);
		if ($this->conn->connect_errno) {
			echo "Error";
		}
		else{
			return $this->conn;
		}
	}

	public function close(){
		$this->conn->close();
	}

	public function query($query){
			$this->connect();
			if(!$this->conn->query($query)){
				return false;
			}else{
				return true;
			}
			$this->close();
	}

	public function delete($table, $where){
		$sql = "DELETE FROM ".$tabla. " WHERE ".$where;
		if ($this->query($sql)==false) {
			return 'Error';
		}else{
			return 'OK';
		}
	}

	public function create($table, $data=array()){
		foreach ($data as $key => $value) {
			$this->key .= ','.$key;
			$this->value .= ",'".$value."'";
		}
		$sql='INSERT INTO '.$table.'('.substr($this->key, 1, strlen($this->key)).') VALUES('.substr($this->value, 1,strlen($this->value)).');';
		if ($this->query($sql)==false) {
			return 'Error';
		}else{
			return 'OK';
		}
	}
}
?>