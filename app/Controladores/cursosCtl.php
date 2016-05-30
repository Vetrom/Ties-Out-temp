<?php
	/**
	*
	*/
	class Curso
	{

		private $modelo;
		private $header;
		private $footer;
		private $head;

		function __construct(){
			require('app/Controladores/generalCtl.php');

 			//session_start();
			$this->header = file_get_contents("app/Vistas/header.html");
			$this->footer = file_get_contents("app/Vistas/footer.html");
			$this->head = file_get_contents("app/Vistas/head.html");

			$this->generalctl = new General();
			$this->header = $this->generalctl->headerSesion($this->header);
		}

		public function ejecutar(){
				//require_once("modelo/usuario.php");
				//$this->modelo = new UsuarioMdl();
				if(isset($_GET['act'])){
					if(isset($_GET['idcurso'])){
						$idcurso = $_GET['idcurso'];
					}else{
						$idcurso = -1;
					}


					switch ($_GET['act']) {
						case 'mostrar':
								$this->muestraCurso($idcurso);
							break;
						case 'miscursos':
								$this->misCursos();
							break;
						default:
							# code...
							break;
					}
				}
		}

		private function muestraCurso($idcurso){
			if($idcurso>=0){
				switch ($idcurso) {
					case 1:
						$vista = file_get_contents('app/Vistas/curso1.html');
						break;
					case 2:
						$vista = file_get_contents('app/Vistas/curso2.html');
						break;
					default:
						# code...
						break;
				}
			}else{

			}

			echo $this->head . $this->header . $vista . $this->footer;
		}

		private function misCursos(){
			$vista = file_get_contents('app/Vistas/misCursos.html');
			$diccionario = array(
				'{tituloPagina}'=>"Mis cursos");
			$this->head = strtr($this->head,$diccionario);
			echo $this->head . $this->header . $vista . $this->footer;
		}
	}
?>
