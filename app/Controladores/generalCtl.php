<?php
	/**
	*
	*/
	class General
	{

		private $modelo;
		private $head;
		private $header;
		private $footer;

		private $generalctl;
		private $vista;

		function __construct(){
			//session_start();
			//require('app/Modelo/singleton.php');
			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();
			$this->head = file_get_contents('app/Vistas/head.html');
			$this->header = file_get_contents('app/Vistas/header.html');
 			$this->footer = file_get_contents('app/Vistas/footer.html');

			$this->header = $this->headerSesion($this->header);

		}

		public function ejecutar(){
			if(isset($_GET['act'])){
				switch ($_GET['act']) {
					case 'btrabajo':
							$this->btrabajo();
						break;
					case 'contacto':
							$this->contacto();
						break;
					case 'nosotros':
							$this->nosotros();
						break;
					case 'busqueda':
							$this->busqueda();
						break;
					default:
						# code...
						break;
				}

			}else{
				//require('app/Vistas/404.php');
			}
		}

		function btrabajo(){
			$this->vista = file_get_contents("app/Vistas/buscarTrabajo.html");

			$diccionario = array(
				'{tituloPagina}' => "Buscar empleo",
				'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/css/buscaTrabajo.css">');

			$this->head = strtr($this->head,$diccionario);
			$this->vista = $this->head . $this->header . $this->vista . $this->footer;

			echo $this->vista;
		}

		function contacto(){
			$this->vista = file_get_contents("app/Vistas/contacto.html");

			$diccionario = array(
				'{tituloPagina}' => "Contacto");

			$this->head = strtr($this->head,$diccionario);
			$this->vista = $this->head . $this->header . $this->vista . $this->footer;

			echo $this->vista;
		}

		function nosotros(){
			$this->vista = file_get_contents("app/Vistas/informacion.html");

			$diccionario = array(
				'{tituloPagina}' => "Quiénes somos");

			$this->head = strtr($this->head,$diccionario);
			$this->vista = $this->head . $this->header . $this->vista . $this->footer;

			echo $this->vista;
		}

		function busqueda(){
			require('app/Modelo/generalMdl.php');
			$this->modelo = new GeneralMdl($this->mysql);
			if(empty($_POST)){
				$vista = file_get_contents("app/Vistas/busqueda.html");
				$diccionarioBuqueda = array(
					'<!--{curso}-->'=>'<a href="#" class="tituloBusqueda"><h2>No se encontraron resultados.</h2></a>',
					'<!--{contenido}-->'=>'<p>Intente con otra búsqueda...</p>');
				$vista = strtr($vista,$diccionarioBuqueda);
				$diccionario = array(
					'{tituloPagina}' => "Búsqueda");
				$this->head = strtr($this->head,$diccionario);
				$vista = $this->head . $this->header . $vista . $this->footer;
				echo $vista;
			}
			else{
				$buqueda = $_POST["busqueda"];
				$resultado = $this->modelo->buscar($busqueda);
				if($resultado !== FALSE){
					$vista = file_get_contents("app/Vistas/busqueda.html");
					$inicio_fila = strrpos($vista,'<section>');
					$final_fila = strrpos($vista,'</section>') + 10;
					$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
					foreach ($resultado as $row) {
						$new_fila = $fila;
						$diccionario = array(
							'<!--{usuario}-->' => '<a href="#" class="tituloBusqueda">'.$row['usuario'].'</a>',
							'<!--{descripcion}-->' => '<p>'.$row['descripcion'].'</p>',
							'<!--{curso}-->' => '<a href="#" class="tituloBusqueda">'.$row["curso"].'</a>',
							'<!--{contenido}-->' => "<p>".$row['contenido']."</p>");
						$filas .= $new_fila;
					}
					$vista = str_replace($fila, $filas, $vista);
					$diccionario = array(
						'{tituloPagina}' => "Búsqueda");
					$this->head = strtr($this->head,$diccionario);
					$this->vista = $this->head . $this->header . $vista . $this->footer;
					echo $this->vista;
				}
			}
		}

		public static function headerSesion($header){


			if(isset($_SESSION) && !empty($_SESSION)){

				$inicioDesconectado = strrpos($header, '<!--{iniciodesconectado}-->');
				$finDesconectado = strrpos($header, '<!--{findesconectado}-->') + 24;

				$desconectado = substr($header, $inicioDesconectado,$finDesconectado - $inicioDesconectado);
				$header = str_replace($desconectado, '<!--{Desconectado}-->', $header);

				$header = str_replace('<!--{nombreUsuario}-->', $_SESSION['nombre'], $header);

				$header = str_replace('{idUsuario}', $_SESSION['idUsuario'], $header);
				$header = str_replace('{banderaSesion}', 'cerrarSesion', $header);

			}else{
				$inicioConectado = strrpos($header, '<!--{inicioconectado}-->');
				$finConectado = strrpos($header, '<!--{finconectado}-->') + 21;

				$conectado = substr($header, $inicioConectado, $finConectado - $inicioConectado);

				$header = str_replace($conectado,'<!--{Conectado}-->', $header);
				$header = str_replace('{banderaSesion}', 'sesion', $header);
			}

			return $header;
		}
	}
?>
