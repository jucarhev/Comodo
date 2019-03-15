<?php
/**
 *  Clase Comodo: una clase para manejo de php poo con mysqli
 *
 *  Read more {@link https://github.com/pacpac1992/Comodo here}
 *
 *  @author     JC <juankarlos.0304@gmail.com>
 *  @version    1.5 (last revision: February 8, 2018)
 *  @copyright  (c) 2010 - 2020 jc
 *  @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 *  @package    Comodo
 */
class Comodo{

	// Variable que almacena la conexion
	private $conn;

	// dentro del array asociativo, es para retornar los nombre de las columnas
	private $value='';

	// dentro del array asociativo, es para retornar los valores de las columnas
	private $key='';

	// provee de los datos para la conexion
	private $credencial=array();
	
	/**
     *  Este es el constructor de la clase, donde se inicializa la conexion a la base de datos
     *  para ello es necesario instanciar la clase y llamarla con un objeto
     *
     *  <code>
     * 		include_once "Comodo.php";
     * 		$obj = new Comodo("hostname","user","password","database");
     *  </code>
     *
     *  @param  string  $host 			Contiene el nombre del host, puede ser 'localhost' o '127.0.0.1'
     * 									Este parametro es requerido
     * 
     *  @param  string  $user 			Contiene el nombre del usuario, por defecto suele ser 'root'
     *  								Este parametro es requerido
     * 
     *  @param  string  $pass 			Contiene el password del host
     * 									Este parametro es requerido
     * 
     *  @param  string  $db 			Contiene el nombre de la base de datos
     * 									Este parametro es requerido
     * 
     *  @param  string  $port 			Contiene el nombre del puerto de conexion, por lo regular se usa por defecto el 3306
     * 									Este parametro es opcional
     *  
     *  @param  string  $socket 		Contiene el nombre del socket, por lo regular es esta en /tmp/mysql.sock
     * 									Este parametro es opcional
     *
     *  @since  1.0
     *
     *  @return string                 No retorna nada solo inicializa el array para la conexion
     */
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

	/**
     *  Realiza la conexion y la retorna para su uso posterior, pero solo se usa por los metodos internos
     *
     *  <code>
     * 		// No necesario
     *  </code>
     *
     *  @since  1.0
     *
     *  @return obj                 Retorna el objeto conexion
     */
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

	/**
     *  Cierra la conexion a la base de datos, esta funcion es utilizada por los metodos internos
     */
	public function close(){
		$this->conn->close();
	}

	#--------> Funciones basicas de un crud

	/**
     *  Funcion query, solo sirve para hacer guardado de datos, eliminacion o actualizacion
     *  no para realizar consultas
     *
     *  <code>
     *  	$obj->query("DELETE FROM tabla WHERE id=1");
     * 		$obj->query("UPDATE tabla SET columna='Nuev valor' WHERE id=1");
     * 		$obj->query("INSERT INTO tabla(columna) VALUES('valor')");
     *  </code>
     *
     *  @param  string  $query          Contiene la instruccion para ejecutar sql
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return boolean                 Retorna false o  true
     */
	public function query($query){
			$this->connect();
			if(!$this->conn->query($query)){
				return false;
			}else{
				return true;
			}
			$this->close();
	}

	/**
     *  Inserta los registros a la tabla de base de datos
     *
     *  <code>
     *  $data = array('columna_a_insertar' => 'valor_del_dato');
     *  
     *  $obj->create("tabla_mysql", $data);
     *  </code>
     *
     *  @param  string  $table          Tabla a insertar: Contiene el nombre de la tabla a insertar datos
     * 									Parametro requerido
     * 
     *  @param  array $data      		Contiene los registros a guardar
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return string                 Retorna 'OK' o  'Error'
     */
	public function create($table, $data=array()){
		foreach ($data as $key => $value) {
			$this->key .= ','.$key;
			$this->value .= ",'".$value."'";
		}
		$sql='INSERT INTO '.$table.'('.substr($this->key, 1, strlen($this->key)).') VALUES('.substr($this->value, 1,strlen($this->value)).');';
		if ($this->query($sql)==true) {
			return 'OK';
		}else{
			return 'Error';
		}
	}
	
	/**
     *  Lee los registros dentro de una tabla de base de datos y retorna todo sus valores
     *
     *  <code>
     *  $obj->read("SELECT * FROM TABLA");
     *  </code>
     *
     *  @param  string  $query          Contiene el string para la consulta a la base de datos
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return array                   Retorna array asociativo con los valores
     */
	public function read($query){
		$this->connect();
		$res=$this->conn->query($query);
		$query = $res->fetch_all(MYSQLI_ASSOC);
		$this->close();
		return $query;
	}

	/**
     *  Actualiza los registros a la tabla de base de datos
     *
     *  <code>
     *  	$data = ("nombre_del_campo" =>"nuevo_valor")
     *  	$obj->update("Tabla_a_actualizar",$data,"id=1");
     *  </code>
     *
     *  @param  string  $table          Contiene el nombre de la tabla
     * 									Parametro requerido
     * 
     *  @param  array  $data          	Contiene el un array con los datos para actualizar
     * 									Parametro requerido
     * 
     *  @param  string  $where          Contiene la condicion para la actualizacion
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return string                   Retorna string 'ok' o 'Error'
     */
	public function update($table,$data=array(),$where){
		$this->connect();
		$query="UPDATE ".$table." SET";
		foreach ($data as $key => $val) {
			$this->key.=", ".$key."='".$val."'";
		}
		$query.=substr($this->key, 1, strlen($this->key))." where ".$where.";";
		echo $query;
		if ($this->query($query)==false) {
			return 'Error';
		}else{
			return 'OK';
		}
	}

	/**
     *  Elimina registros de la tabla
     *
     *  <code>
     *  $obj->delete("tabla_de_base_de_datos","id=1");
     *  </code>
     *
     *  @param  string  $table          Contiene el string para la consulta a la base de datos
     * 									Parametro requerido
     * 
     *  @param   string  $where 		Contiene la condicion para eliminar el dato
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return string                   Retorna string 'ok' o 'Error'
     */
	public function delete($table, $where){
		$sql = "DELETE FROM ".$table. " WHERE ".$where;
		if ($this->query($sql)==false) {
			return 'Error';
		}else{
			return 'OK';
		}
	}

	#--------> End funciones basicas


	/**
     *  Inserta los registros a la tabla de base de datos
     *
     *  <code>
     *  $obj->table("tabla_de_base_datos","class='table' border=1");
     *  </code>
     *
     *  @param  string  $table          Contiene el string para la consulta a la base de datos
     * 									Parametro requerido
     * 
     *  @param  string  $param          Contiene los parametros para la etiqueta <table>, como la clase
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return string                  Retorna un string con la tabla hecha en html
     */
	public function table($table, $param=''){
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

	/**
     *  Cuenta el numero de registros de una base de datos y devuelve el numero de estos
     *
     *  <code>
     *  $obj->count("SELECT * FROM TABLA");
     *  </code>
     *
     *  @param  string  $sql          	Contiene el string para el conteo de registros
     * 									Parametro requerido
     *
     *  @since  1.0
     *
     *  @return integer                 Retorna el numero de valores
     */
	public function count($sql){
		$this->connect();
		$res=$this->conn->query($sql);
		$total=$res->num_rows;
		return $total;
	}

	/**
     *  Funcion truncate, vacia las tablas de base de datos
     *
     *  <code>
     *  $obj->truncate('Tabla_a_vaciar');
     *  </code>
     *
     *  @param  string  $table          Contiene el nombre de la tabla a vaciar
     * 									Parametro requerido
     * 
     *  @since  1.0
     *
     *  @return string                  Retorna string 'ok' o 'Error'
     */
	public function truncate($table){
		$sql = "TRUNCATE TABLE  ".$table;
		if ($this->query($sql)==false) {
			return 'Error';
		}else{
			return 'OK';
		}
	}
}
?>