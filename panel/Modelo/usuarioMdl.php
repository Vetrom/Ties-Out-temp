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
		$query = "SELECT vchNombre FROM usuario U WHERE U.tiActivo=1 AND U.iidTipo = 2 AND U.vchcorreo LIKE '%".$correo."%' AND U.vchcontrasena LIKE '%".$contrasena."%'";
		$resultado = $this->mysql->query($query);
		while($fila = $resultado->num_rows){
			if($fila > 0)
				return true;
		}
		return false;
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

	function traerUsuarios(){
		$info ="";
		$query = "SELECT  upper( vchNombre ) AS nombre, vchCorreo FROM usuario WHERE tiActivo =1 AND tiEliminado =0 AND iidTipo =1";
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
