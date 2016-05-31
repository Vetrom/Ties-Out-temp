<?php
class ExamenMdl{

	private $mysql;

	function __construct($mysql){
		$this->mysql = $mysql;
	}

    function traerExamenes(){
		$info ="";
		$query = "SELECT  E.iidExamen as id , upper( E.vchNombre ) AS nombre, E.iValor as valor, C.vchNombre as curso,
                CASE WHEN E.tiActivo =1 THEN 'Usuario activado' ELSE 'Usuario desactivado' END as estado
                FROM examen E
                INNER JOIN curso C
                ON C.iidCurso = E.iidCurso
                WHERE E.tiEliminado = 0";
		$resultado = $this->mysql->query($query);
		while($fila = $resultado->num_rows){
			if($fila > 0)
				while($fila = $resultado->fetch_assoc())
					$info[] = $fila;
		return $info;
		}
		return false;
	}

	function guardarExamen($arreglo, $id){
		$query = "INSERT INTO examen(vchNombre, iValor,tiEliminado,tiActivo, dfecha, iidCurso)
				VALUES('".$arreglo["titulo"]."',100,0,1,NOW(),$id)";
		$result = $this->mysql->query($query);
		if($this -> mysql-> insert_id){
			$this->guardarPregunta($arreglo,$this -> mysql-> insert_id);
		}
		elseif($result === FALSE)
			return FALSE;
	}

	function guardarPregunta($arreglo, $id){
		echo "hola";
	}

	function existeCurso($nombre){
		$query = "SELECT iidCurso FROM curso WHERE vchNombre = '".$nombre."' LIMIT 1 ";
		while($fila = $resultado->num_rows){
			if($fila > 0)
				$fila = $resultado->fetch_assoc())
		return $fila;
		}
		return false;
	}
}

?>
