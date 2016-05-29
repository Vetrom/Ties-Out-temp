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
			session_start();
			$this->head = file_get_contents('app/Vistas/head.html');
+			$this->header = file_get_contents('app/Vistas/header.html');
 			$this->footer = file_get_contents('app/Vistas/footer.html');

+			$this->header = $this->headerSesion($this->header);

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
				require('app/Vistas/404.php');
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
			$this->vista = file_get_contents("app/Vistas/busqueda.html");

			$diccionario = array(
				'{tituloPagina}' => "Búsqueda");

			$this->head = strtr($this->head,$diccionario);
			$this->vista = $this->head . $this->header . $this->vista . $this->footer;

			echo $this->vista;
		}

		public static function headerSesion($header){


			if(isset($_SESSION) && !empty($_SESSION)){
				var_dump($_SESSION);
				$inicioDesconectado = strrpos($header, '<!--{iniciodesconectado}-->');
				$finDesconectado = strrpos($header, '<!--{findesconectado}-->') + 24;

				$desconectado = substr($header, $inicioDesconectado,$finDesconectado - $inicioDesconectado);
				$header = str_replace($desconectado, '<!--{Desconectado}-->', $header);

				$header = str_replace('<!--{nombreUsuario}-->', $_SESSION['nombre'], $header);

			}else{
				$inicioConectado = strrpos($header, '<!--{inicioconectado}-->');
				$finConectado = strrpos($header, '<!--{finconectado}-->') + 21;

				$conectado = substr($header, $inicioConectado, $finConectado - $inicioConectado);
				$header = str_replace($conectado,'<!--{Conectado}-->', $header);
			}

			return $header;
		}
	}
?>
