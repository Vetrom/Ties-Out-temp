<?php
	class AdministradorMdl{

	private $driver;

	function __construct(){
		$this -> driver = new mysqli($server,$user,$pass,$namedb);
		if($this -> driver->connect_errno)
			die("<br>Error en la conexión");
	}

	function alta($nombre, $codigo, $carrera, $correo){

		$query =
				"INSERT INTO administrador
				(nombre, correo)
				VALUES (
					\"$nombre\",
					\"$correo\"
				)";

		$r = $this -> driver -> query($query);

		if($this -> driver -> insert_id){
			return $this -> driver -> insert_id;
		}
		elseif($r === FALSE)
			return FALSE;
	}

	function lista(){

		$query = 'SELECT * FROM administrador';
		$r = $this -> driver -> query($query);
		while($row = $r -> fetch_assoc())
			$rows[] = $row;
		return $rows;
	}
}
