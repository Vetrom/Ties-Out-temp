<?php
class GeneralMdl{

	private $mysql;

	function __construct($mysql){
		$this->mysql = $mysql;
	}

	function buscar($stringBuscar){
        $info = "";
		$query = "SELECT U.vchNombre AS usuario, U.vchDescripcion AS descripcion FROM usuario U
                 WHERE U.vchNombre LIKE '%".$stringBuscar."%'  AND U.tiActivo = 1 AND U.tiEliminado = 0
				 union all
				 select C.vchNombre AS curso, SUBSTRING( C.vchContenido, 100 ) AS contenido
				 FROM curso C
				 WHERE C.vchNombre LIKE '%".$stringBuscar."%' AND C.tiActivo = 1 AND C.tiEliminado = 0";
		$resultado = $this->mysql->query($query);
		$fila = $resultado->fetch_assoc();
		return $fila;
    }
}
