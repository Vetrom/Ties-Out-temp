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
		private $instancia;
		private $mysql;
		private $generalctl;

		function __construct(){
			require('Controladores/generalCtl.php');
			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();
// 			session_start();
			$this->header = file_get_contents("Vistas/header.html");
			$this->footer = file_get_contents("Vistas/footer.html");
			$this->head = file_get_contents("Vistas/head.html");

			$this->generalctl = new General();
			$this->header = $this->generalctl->headerSesion($this->header);
		}

		public function ejecutar(){
				//require_once("modelo/usuario.php");
				//$this->modelo = new UsuarioMdl();
				if(isset($_GET['act'])){
					switch ($_GET['act']) {
						case 'mostrar':
								$this->muestraCursos();
							break;
						case 'agregar':
								$this->agregarCursos();
							break;
						case 'guardar':
								$this->guardarCursos();
							break;
						case 'eliminar':
								$this->eliminarCursos();
							break;
						case 'desactivar':
								$this->desactivarCursos();
							break;
						case 'activar':
								$this->activarCursos();
							break;
						default:
							# code...
							break;
					}
				}
		}

		private function muestraCursos(){

			require('Modelo/cursosMdl.php');
			$this->modelo = new CursosMdl($this->mysql);

			$diccionario = "";
			$diccionarioCuros = "";
			$filas = "";
			$vista = file_get_contents('Vistas/listas/cursos.html');
			$inicio_fila = strrpos($vista,'<!--{cursos}-->');
			$final_fila = strrpos($vista,'<!--{cursosT}-->') + 16;
			$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
			//Genero las filas
			$alumnos = $this->modelo->traerCursos();
			foreach ($alumnos as $row) {
				$new_fila = $fila;
				$diccionarioCursos = array(
					'<!--{id}-->' => $row['id'],
					'<!--{nombre}-->' => $row['nombre'],
					'<!--{estado}-->' => $row['estado'],
					'<!--{descripcion}-->' => $row['contenido']);
				$new_fila = strtr($new_fila,$diccionarioCursos);
				$filas .= $new_fila;
			}
			//Reemplazo en mi vista una fila por todas las filas
			$vista = str_replace($fila, $filas, $vista);
			$diccionario = array(
				'{tituloPagina}'=> "Cursos",
				'<!--{masLinks}-->'=> '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css"/>  <link rel="stylesheet" type="text/css" href="../recursos/js/paginacion/simplePagination.css"/>  <script type="text/javascript" src="../recursos/js/paginacion.js"></script>',
				'<!--{otros}-->' => '<script type="text/javascript" src="../recursos/js/paginacion/jquery.simplePagination.js"></script>');

			$this->head = strtr($this->head,$diccionario);
			$vista = $this->head . $this->header . $vista . $this->footer;

			echo $vista;
		}

		private function agregarCursos(){

			$vista = file_get_contents("Vistas/agregarcurso.html");
			$diccionario = array(
			'{tituloPagina}'=>"Agregar cursos",
			'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
			$this->head = strtr($this->head,$diccionario);
			echo $this->head . $this->header . $vista . $this->footer;
		}

		private function guardarCursos(){
			$titulo = "";
			$contenido = "";
			require('Modelo/cursosMdl.php');
			$this->modelo = new CursosMdl($this->mysql);

			if(empty($_POST)){
				$this->mostrarProblema("Llene los campos para guardar el curso.");
			}else{
				$titulo = $_POST['titulo'];
				$contenido = $_POST['contenido'];

				$resultado = $this->modelo->guardarCurso($titulo, $contenido);
				if($resultado!==FALSE){
					$vista = file_get_contents("Vistas/listas/cursos.html");
					$diccionario = array(
					'{tituloPagina}'=>"Cursos",
					'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
					$this->head = strtr($this->head,$diccionario);
					echo $this->head . $this->header . $vista . $this->footer;
				}
				else {
					$this->mostrarProblema("No se pudo guardar el curso. Intente más tarde.");
				}
			}
		}

		private function eliminarCursos(){
			require('Modelo/cursosMdl.php');
			$this->modelo = new CursosMdl($this->mysql);
			$id = $_GET['id'];
			if($id == ""){
				$this->mostrarProblemaC("Error al eliminar curso.");
			}
			else {
				$resultado = $this->modelo->eliminarCursos($id);
				if($resultado){
					$vista = file_get_contents("Vistas/listas/cursos.html");
					$diccionario = array(
					'{tituloPagina}'=>"Cursos",
					'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
					$this->head = strtr($this->head,$diccionario);
					echo $this->head . $this->header . $vista . $this->footer;
				}
				else {
					$this->mostrarProblemaC("No se puede eliminar el curso.");
				}
			}
		}

		private function desactivarCursos(){
			require('Modelo/cursosMdl.php');
			$this->modelo = new CursosMdl($this->mysql);
			$id = $_GET['id'];
			if($id == ""){
				$this->mostrarProblemaC("Error al desactivar curso.");
			}
			else {
				$resultado = $this->modelo->desactivarCursos($id);
				if($resultado){
					$vista = file_get_contents("Vistas/listas/cursos.html");
					$diccionario = array(
					'{tituloPagina}'=>"Cursos",
					'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
					$this->head = strtr($this->head,$diccionario);
					echo $this->head . $this->header . $vista . $this->footer;
				}
				else {
					$this->mostrarProblemaC("No se puede desactivar el curso.");
				}
			}
		}

		private function activarCursos(){
			require('Modelo/cursosMdl.php');
			$this->modelo = new CursosMdl($this->mysql);
			$id = $_GET['id'];
			if($id == ""){
				$this->mostrarProblemaC("Error al activar curso.");
			}
			else {
				$resultado = $this->modelo->activarCursos($id);
				if($resultado){
					$vista = file_get_contents("Vistas/listas/cursos.html");
					$diccionario = array(
					'{tituloPagina}'=>"Cursos",
					'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
					$this->head = strtr($this->head,$diccionario);
					echo $this->head . $this->header . $vista . $this->footer;
				}
				else {
					$this->mostrarProblemaC("No se puede activar el curso.");
				}
			}
		}

		private function mostrarProblema($string){
			$vista = file_get_contents("Vistas/agregarcurso.html");
			$diccionarioP = array(
			'<!--{problema}-->' => '<h4 class="text-danger">'.$string.'</h4>');
			$vista = strtr($vista,$diccionarioP);
			$diccionario = array(
			'{tituloPagina}'=>"Agregar cursos",
			'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
			$this->head = strtr($this->head,$diccionario);
			echo $this->head . $this->header . $vista . $this->footer;
		}

		private function mostrarProblemaC($string){
			$vista = file_get_contents("Vistas/listas/cursos.html");
			$diccionarioP = array(
			'<!--{problema}-->' => '<h4 class="text-danger">'.$string.'</h4>');
			$vista = strtr($vista,$diccionarioP);
			$diccionario = array(
			'{tituloPagina}'=>"Cursos",
			'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
			$this->head = strtr($this->head,$diccionario);
			echo $this->head . $this->header . $vista . $this->footer;
		}
	}
?>
