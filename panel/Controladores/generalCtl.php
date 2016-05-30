<?php
	/**
	*
	*/
	class General
	{
		private $modelo;
		private $header;
		private $generalctl;

		function __construct(){
			//session_start();
			//require('app/Modelo/singleton.php');
			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();
			$this->mysql = $this->instancia->getConnection();
			$this->header = file_get_contents('Vistas/header.html');
			$this->header = $this->headerSesion($this->header);
		}

		public static function headerSesion($header){
			if(isset($_SESSION) && !empty($_SESSION)){
				$header = file_get_contents('Vistas/header.html');
				return $header;
			}else{
				return "";
			}
		}
	}
?>
