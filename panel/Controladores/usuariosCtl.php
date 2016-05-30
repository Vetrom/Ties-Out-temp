<?php
	/**
	*
	*/
	class Usuario
	{


		private $modelo;
		private $head;
		private $header;
		private $footer;
		private $instancia;
		private $mysql;
		private $generalctl;

		function __construct(){
			//session_start();
			require('Controladores/generalCtl.php');
			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();

			$this->generalctl = new General();
			$this->header = file_get_contents("Vistas/header.html");
			$this->header = $this->generalctl->headerSesion($this->header);
			$this->head = file_get_contents('Vistas/head.html');
			$this->footer = file_get_contents('Vistas/footer.html');
		}

		/**
		* Método que realiza una acción según el valor enviado en GET con la llave act
		*
		*/
		public function ejecutar(){


			if(isset($_GET['act'])){
				if(isset($_GET['iduser'])){
					$idUsuario = $_GET['iduser'];
				}else{
					$idUsuario = -1;
				}


				switch ($_GET['act']) {
					case 'mostrar':
							$this->mostrarUsuarios();
						break;
					case 'registrar':
							//Aqui llegar para conectarse a la base de datos por medio del modelo
							$this->altaUsuario();
						break;
					case 'inicioSesion':
							$this->iniciaSesionUsuario();
						break;
					case 'cerrarSesion':
							$this->cerrarSesion();
						break;
					default:
							//require('404.php');
						break;
				}
			}else{
				//require('404.html');
			}
		}

		/**
		* Muestra un formulario indicado por el parámetro tipo
		* @param $tipo El valor 1 abre el formulario de sesion, el valor 2 el formulario de registro, 3 recuperar contraseña
		*/
		private function mostrarUsuarios(){
			require('Modelo/usuarioMdl.php');
			$this->modelo = new UsuarioMdl($this->mysql);

			$diccionario = "";
			$diccionarioUsuarios = "";
			$filas = "";
			$vista = file_get_contents('Vistas/listas/usuarios.html');
			$inicio_fila = strrpos($vista,'<!--{usuarios}-->');
			$final_fila = strrpos($vista,'<!--{usuariosT}-->') + 18;
			$fila = substr($vista,$inicio_fila,$final_fila-$inicio_fila);
			//Genero las filas
			$alumnos = $this->modelo->traerUsuarios();
			foreach ($alumnos as $row) {
				$new_fila = $fila;
				$diccionario = array(
					'<!--{nombre}-->' => $row['nombre'],
					'<!--{correo}-->' => $row['vchCorreo']);
					$new_fila = strtr($new_fila,$diccionario);
					$filas .= $new_fila;
				}

			//Reemplazo en mi vista una fila por todas las filas
			$vista = str_replace($fila, $filas, $vista);
			$diccionario = array(
				'{tituloPagina}'=> "Usuarios",
				'<!--{masLinks}-->'=> '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css"/>');

			$this->head = strtr($this->head,$diccionario);
			$vista = $this->head . $this->header . $vista . $this->footer;

			echo $vista;
		}

		private function altaUsuario(){
			require('app/Modelo/usuarioMdl.php');
			$this->modelo = new UsuarioMdl($this->mysql);//se le manda la variable con la conexion establecida
			if(empty($_POST)){
				$this->mostrarProblemaRegistro("Favor de llenar los campos requeridos");
			}else{
				$nombre = $_POST["nombre"];
				$correo	= $_POST["correo"];
				$contrasena = $_POST["contrasena"];

				//Valida si lo que se recibio es un correo
				if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$correo)){
					$this->mostrarProblemaRegistro("Ingrese un correo válido");
					exit();
				}

				//Valida si lo que se recibio es un nombre
				if(!preg_match('/^([a-zA-Z]+[\s]*)+$/',$nombre)){
					$this->mostrarProblemaRegistro("Ingrese un nombre válido");
					exit();
				}

				//Valida si lo que se recibio es una contraseña
				if(!preg_match('/^([a-zA-Z0-9]{6,20})$/',$contrasena)){
					$this->mostrarProblemaRegistro("La contraseña debe contener minimo 6 caracteres alfanuméricos");
					exit();
				}

				//Revisa en la BD si el correo ya existe
				if(!$this->modelo->existecorreo($correo)){
					$contrasena = md5($contrasena); //se encripta la contraseña
					$resultado = $this->modelo->alta($nombre, $correo, $contrasena);//damos de alta en la BD
					if($resultado!==FALSE){//Si se pudo insertar muestra la vista

						$vista = file_get_contents("app/Vistas/home.html");
						$diccionario = array(
						'{tituloPagina}'=>"Inicio",
						'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
						$this->head = strtr($this->head,$diccionario);
						$vista = $this->head . $this->header . $vista . $this->footer;
						echo $vista;
					}
					else{
						$this->mostrarProblemaRegistro("No se pudo completar el registro, intente más tarde.");
					}
				}
				else {
					$this->mostrarProblemaRegistro("El correo ya existe, intente con otro");
				}
			}
		}

		private function iniciaSesionUsuario(){
			require('Modelo/usuarioMdl.php');
			$this->modelo = new UsuarioMdl($this->mysql);

			if(empty($_POST)){
				$this->mostrarProblemaIniciosesion("Ingrese un correo y contraseña para poder iniciar sesión.");
			}else{
				$correo = $_POST['correo'];
				$contrasena = $_POST['contrasena'];

				//Valida si lo que se recibio es un correo
				if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$correo)){
					$this->mostrarProblemaIniciosesion("Ingrese un correo válido");
					exit();
				}

				//Valida si lo que se recibio es una contraseña
				if(!preg_match('/^([a-zA-Z0-9]{6,20})$/',$contrasena)){
					$this->mostrarProblemaIniciosesion("La contraseña debe contener minimo 6 caracteres alfanuméricos");
					exit();
				}

				$contrasena = md5($contrasena); //encriptamos primero para poder comparar con la contraseña de la BD
				//Revisa si el usuario existe en la base de datos
				$resultado = $this->modelo->consultaUsuario($correo, $contrasena);
				if($resultado){
					$_SESSION['correo'] = $correo;
					$_SESSION['contrasena'] = $contrasena;
					$_SESSION['nombre'] = $resultado['vchnombre'];

					$this->header = $this->generalctl->headerSesion($this->header);
					$vista = file_get_contents("Vistas/panel.html");
					$diccionario = array(
					'{tituloPagina}'=>"Inicio",
					'<!--{otros}-->' => '<link rel="stylesheet" type="text/css" href="../recursos/css/panel/simple-sidebar.css">');
					$this->head = strtr($this->head,$diccionario);
					$vista = $this->head . $this->header . $vista . $this->footer;
					echo $vista;
				}else{
					$this->mostrarProblemaIniciosesion("El usuario y/o contraseña es incorrecto. Intente de nuevo.");
				}
			}
		}

		/**
		* Método para cerrar la sesión del usuario
		* @param
		*/
		private function cerrarSesion(){
			if(isset($_SESSION)){
				session_unset();
				session_destroy();
				setcookie(session_name(), '', time()-3600);

				$vista = file_get_contents("Vistas/home.html");

				$diccionario = array(
					'{tituloPagina}'=>"Iniciar sesión");
				$this->head = strtr($this->head,$diccionario);
				echo $this->head . $vista . $this->footer;
			}else{
				//No hay sesión iniciada
			}
		}

		/**
		 * Método para mostrar errores o problemas con la información recibida
		 * @param $string, cadena con el texto a mostrar en la vista. */
		private function mostrarProblemaIniciosesion($string){
			$vista = file_get_contents('Vistas/home.html');
			$diccionario = array(
			'{tituloPagina}'=>"Iniciar sesión");
			$diccionarioProblema = array('<!-- Problema -->'=>'<span class="text-danger">'.$string.'</span>');
			$vista = strtr($vista,$diccionarioProblema);
			$this->head = strtr($this->head, $diccionario);
			$vista = $this->head . $this->header . $vista . $this->footer;
			echo $vista;
		}
	}
?>
