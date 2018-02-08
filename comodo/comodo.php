<?php  
/**
* Clase Comodo
*/
class Comodo
{
	var $css_table;
	var $css_campo;

	var $conn;
	var $credencial=array();
	var $value='';
	var $key='';

	var $OK="OK";
	var $ERROR="ERROR";

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
	public function create($table, $data=array()){
		foreach ($data as $key => $value) {
			$this->key .= ','.$key;
			$this->value .= ",'".$value."'";
		}
		$sql='INSERT INTO '.$table.'('.substr($this->key, 1, strlen($this->key)).') VALUES('.substr($this->value, 1,strlen($this->value)).');';
		if ($this->query($sql)==false) {
			return $ERROR;
		}else{
			return $this->OK;
		}
	}
	public function update($table,$data=array()){
		$this->connect();
		$query="UPDATE ".$table." SET";
		foreach ($data as $key => $val) {
			$this->key.=", ".$key."='".$val."'";
		}
		$query.=substr($this->key, 1, strlen($this->key))." where ".$where.";";
		if ($this->query($sql)==false) {
			return $ERROR;
		}else{
			return $this->OK;
		}
	}
	public function delete($table, $where){
		$sql = "DELETE FROM ".$tabla. " WHERE ".$where;
		if ($this->query($sql)==false) {
			return $ERROR;
		}else{
			return $this->OK;
		}
	}
	public function read($table, $param=''){
		$table_datos="<table ".$param."><tr>";
		$this->connect();
		$query = "SELECT * from ".$table;
		$resultado=$this->conn->query($query);
		$datosvalores2="";
		$datosvalores='';

		if ($resultado = mysqli_query($this->conn, $query)) {
		    $info_campo = mysqli_fetch_fields($resultado);

		    foreach ($info_campo as $valor) {
		        $datosvalores.=",".strtoupper($valor->name);
		        $datosvalores2.=",".$valor->name;
		    }
		    mysqli_free_result($resultado);
		}

		$campos_limpios2=substr($datosvalores2,1,strlen($datosvalores2));
		$campos_limpios=substr($datosvalores,1,strlen($datosvalores));

		$arreglo = explode(",", $campos_limpios);
		$arreglo2 = explode(",", $campos_limpios2);

		$conteo=count($arreglo);
		for ($i=0; $i < $conteo; $i++) { 
			$table_datos.="<th>".$arreglo[$i]."</th>";
		}

		$sentencia=$this->conn->query($query);
		$total=$sentencia->num_rows;

		if ($sentencia->num_rows>0) {
			while ($fila=$sentencia->fetch_array()) {
				$table_datos.="<tr>";
				for ($i=0; $i < $conteo; $i++) { 
					$table_datos.="<td>".$fila[$arreglo2[$i]]."</td>";
				}

				$table_datos.="</tr>";
			}

		}else
		{
			echo "No hay registros";
		}

		$table_datos.="</tr>";
		$table_datos.="</table>";
		return $table_datos;
	}
	public function count($sql){
		$this->connect();
		$res=$this->conn->query($sql);
		$total=$res->num_rows;
		return $total;
	}
	public function truncate($table){
		$sql = "TRUNCATE TABLE  ".$table;
		if ($this->query($sql)==false) {
			return $ERROR;
		}else{
			return $this->OK;
		}
	}
	public function get_row($table, $id){}
	public function pagination(){}
	public function table_advance($table,$data=array(),$where='',$limit='',$option=''){
		$this->connect();
		$sql="";
		$tabla="<table><tr>";
		#$total = $this->count("SELECT * from ".$table);
		$num=count($data);
		for ($i=0; $i < $num; $i++) { 
			$tabla.="<td>".$data[$i]."</td>";
		}
		$tabla.="</tr>";
		foreach ($data as $value) {
			$sql.=",".$value;
		}
		$sql = substr($sql, 1, strlen($sql));
		$sql = "SELECT ".$sql." FROM ".$table.";";

		$sentencia=$this->conn->query($sql);
		$total=$sentencia->num_rows;

		while ($fila=$sentencia->fetch_array()) {
			$tabla.="<tr>";
			for ($i=0; $i < $num; $i++) { 
				$tabla.="<td>".$fila[$i]."</td>";
			}
				$tabla.="</tr>";
		}
		
		$tabla.="</tr>"; 
		$tabla.="</table>";
		return $tabla.$sql;
	}
	public function rediret(){}
}
?>