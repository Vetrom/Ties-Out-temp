<?php
class CursosMdl{

	private $mysql;

	function __construct($mysql){
		$this->mysql = $mysql;
	}

    function guardarCurso($titulo, $contenido){
        $query = "INSERT INTO curso(vchNombre, ltextContenido, dfechaIn)
                VALUES('".$titulo."','".$contenido."',NOW())";
        $result = $this->mysql->query($query);
		if($this -> mysql-> insert_id){
			return $this -> mysql -> insert_id;
		}
		elseif($result === FALSE)
			return FALSE;
    }

	function traerCursos(){
		$info ="";
		$query = "SELECT  upper( vchNombre ) AS nombre, ltextContenido AS contenido FROM curso WHERE tiActivo =1 AND tiEliminado =0";
		$resultado = $this->mysql->query($query);
		while($fila = $resultado->num_rows){
			if($fila > 0)
				while($fila = $resultado->fetch_assoc())
					$info[] = $fila;
		return $info;
		}
		return false;
	}


}
?>
