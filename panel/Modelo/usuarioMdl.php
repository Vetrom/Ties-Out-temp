<?php
class UsuarioMdl{

	private $mysql;

	function __construct($mysql){
		$this->mysql = $mysql;
	}

	function alta ($nombre, $correo, $contrasena){

		$query = "INSERT INTO usuario (vchNombre,vchCorreo, vchContrasena) VALUES ('$nombre','$correo','$contrasena')";
		$result = $this->mysql->query($query);
		if($this -> mysql-> insert_id){
			return $this -> mysql -> insert_id;
		}
		elseif($result === FALSE)
			return FALSE;
	}

	function consultaUsuario($correo, $contrasena){
		$info = "";
		$query = "SELECT vchnombre FROM usuario WHERE vchcorreo='$correo' AND vchcontrasena='$contrasena' AND tiActivo = 1 AND iidTipo = 2";
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

	function eliminaUsuario($nombre, $correo, $contrasena){
		$query = "UPDATE usuario SET tiEliminado = 1, tiActivo = 0 WHERE vchNombre = '$nombre' AND vchCorreo = '$correo' AND vchContrasena = '$contrasena'";

		$resultado = $this -> mysql-> query($query);
		$fila = $resultado->fetch_assoc();
		return $fila;
	}

}
