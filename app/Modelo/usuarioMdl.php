<?php
class UsuarioMdl{

	private $mysql;

	function __construct($mysql){
		$this->mysql = $mysql;
	}

	function alta ($nombre, $correo, $contrasena){
		/*$query = "INSERT INTO usuario(vchNombre, vchPaterno, vchMaterno, vchCorreo, vchContraseña,tiEliminado, tiActivo, iidTipo)
				VALUES('$nombre','uno','dos','$correo','$contrasena',0,1,1)";*/
		//La siguiente query es segun la otra base de datos
		$query = "INSERT INTO usuario (vchNombre,vchCorreo, vchContrasena) VALUES ('$nombre','$correo','$contrasena')";
		$result = $this->mysql->query($query);
		if($this -> mysql-> insert_id){
			return $this -> mysql -> insert_id;
		}
		elseif($result === FALSE)
			return FALSE;
	}

	function actualiza($nacimiento, $sexo, $ocupacion, $descripcion, $idUsuario){
		$query = "UPDATE usuario 
				  SET vchSexo='$sexo', vchOcupacion='$ocupacion', vchdescripcion = '$descripcion', dfechaNacimiento = '$nacimiento'
				  WHERE iidUsuario='$idUsuario'";
		$result = $this->mysql->query($query);		
	}

	function consultaUsuario($correo, $contrasena){
		$info = "";
		$query = "SELECT * FROM usuario WHERE vchcorreo='$correo' AND vchcontrasena='$contrasena' AND tiActivo = 1";
		$resultado = $this->mysql->query($query);
		$fila = $resultado->fetch_assoc();
		return $fila;
	}

	function consultaPerfil($id){
		$info = "";
		$query = "SELECT vchCorreo, vchNombre, vchOcupacion, dfechaNacimiento FROM usuario WHERE iidUsuario = '$id' AND tiActivo = 1";
		$resultado = $this->mysql->query($query);
		$fila = $resultado->fetch_assoc();
		return $fila;
	}

	function existecorreo($correo){
		$query = "SELECT vchCorreo FROM usuario WHERE vchCorreo = '$correo' AND tiActivo = 1 AND tiEliminado = 0";
		$resultado = $this->mysql->query($query);
		while($fila = $resultado->num_rows){
			if($fila > 0)
				return true;
		}
		return false;
	}

	function registraCursoUsuario($idUsuario, $idCurso){
		$query = "INSERT INTO curso_usuario (iidCurso, iidExamen, iidUsuario) VALUES ('$idCurso',1,'$idUsuario')";
		$result = $this->mysql->query($query);
		if($this -> mysql-> insert_id){
			return $this -> mysql -> insert_id;
		}
		elseif($result === FALSE)
			return FALSE;
	}
}
