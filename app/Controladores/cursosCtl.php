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

		private $mysql;
		private $instancia;

		function __construct(){
			require('app/Controladores/generalCtl.php');

			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();

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
			require('app/Modelo/cursosMdl.php');
			//echo "D:";
			$this->modelo = new CursosMdl($this->mysql);

			$resultado = $this->modelo->traerCursos($idcurso);

			$vista = file_get_contents('app/Vistas/curso1.html');

			$cursoUsuario = $this->modelo->esCursoUsuario($idcurso, $_SESSION['idUsuario']);
			
			if(!empty($cursoUsuario)){
				$inicio = strrpos($vista,'<!--{inicioNoInscrito}-->');
				$fin = strrpos($vista,'<!--{finNoInscrito}-->') + 22;
				$aux = substr($vista, $inicio , $fin - $inicio);
				$vista = str_replace($aux, "", $vista);
			}

			$diccionario = array(
				'{titulo}' => $resultado['vchNombre'],
				'{contenido}' => $resultado['ltextContenido'],
				'{cursoActual}' => $idcurso);

			$vista = strtr($vista,$diccionario);

			//echo "D:";	
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
