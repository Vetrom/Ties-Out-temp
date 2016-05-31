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
		$query = "SELECT iidCurso AS id, upper( vchNombre ) AS nombre, ltextContenido AS contenido,
				CASE WHEN tiActivo =1 THEN 'Curso activado' ELSE 'Curso desactivado' END as estado
				FROM curso WHERE tiEliminado =0";
		$resultado = $this->mysql->query($query);
		while($fila = $resultado->num_rows){
			if($fila > 0)
				while($fila = $resultado->fetch_assoc())
					$info[] = $fila;
		return $info;
		}
		return false;
	}

	function eliminarCursos($id){
		$query = "UPDATE curso SET tiEliminado = 1, tiActivo = 0 WHERE iidCurso = $id";
		if ($this->mysql->query($query) === TRUE) {
			return true;
		}
		return false;
	}

	function desactivarCursos($id){
		$query = "UPDATE curso SET tiActivo = 0 WHERE iidCurso = $id";
		if ($this->mysql->query($query) === TRUE) {
			return true;
		}
		return false;
	}

	function activarCursos($id){
		$query = "UPDATE curso SET tiActivo = 1 WHERE iidCurso = $id";
		if ($this->mysql->query($query) === TRUE) {
			return true;
		}
		return false;
	}

}
?>
