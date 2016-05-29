<?php
	/**
	*
	*/
	class Busqueda
	{

		private $modelo;
+		private $header;
+		private $footer;
+		private $head;
+		private $generalctl;

 		function __construct(){
 			session_start();
			require('app/Controladores/generalCtl.php');
+
+			$this->head = file_get_contents('app/Vistas/head.html');
+			$this->header = file_get_contents('app/Vistas/header.html');
+			$this->footer = file_get_contents('app/Vistas/footer.html');
+
+			$this->header = $this->generalctl->headerSesion($this->header);
		}

		public function ejecutar(){
	//require_once("modelo/usuario.php");
	//$this->modelo = new UsuarioMdl();
	switch ($_GET['act']) {
+				case 'alta':
+						echo "altaAlumnos.php";
+
+					break;
+				case 'ver':
+						echo "perfilPublico.php";
+					break;
+				case 'configurar':
+						echo "configurarPerfil.php";
+					break;
+				case 'recordar':
+						echo "recordar.php";
+					break;
+				case 'misCursos':
+						echo "misCursos.php";
					break;
		default:
			# code...
			break;
	}
?>
