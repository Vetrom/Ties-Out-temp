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

	function traerCursos($idcurso){
		$info ="";
		$query = "SELECT * FROM curso WHERE iidCurso = '$idcurso'";
		$resultado = $this->mysql->query($query);
		
		$prueba = $resultado->fetch_assoc();

		return $prueba;
		
	}

	function esCursoUsuario($idcurso, $idUsuario){
		$query = "SELECT * FROM curso_usuario WHERE iidCurso='$idcurso' AND iidUsuario='$idUsuario'";
		$resultado = $this->mysql->query($query);

		$info = $resultado->fetch_assoc();
		return $info;
	}

	function getMisCursos($idUsuario){
		$query = "SELECT * FROM curso_usuario WHERE iidUsuario='$idUsuario'";
		$resultado =  $this->mysql->query($query);
		
		while($row = $resultado -> fetch_assoc())
			$rows[] = $row;

		return $rows;
	}
}
?>
 