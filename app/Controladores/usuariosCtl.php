<?php
	/**
	*
	*/
	class Usuario
	{


		private $modelo;
		private $head;
		private $header;
		private $headerOriginal;
		private $footer;
		private $instancia;
		private $mysql;
		private $generalctl;

		function __construct(){
			//require('app/Modelo/singleton.php');
			require('app/Controladores/generalCtl.php');

			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();

			$this->generalctl = new General();

			$this->headerOriginal = file_get_contents("app/Vistas/header.html");
			$this->header = $this->generalctl->headerSesion($this->headerOriginal);
			$this->footer = file_get_contents("app/Vistas/footer.html");
			$this->head = file_get_contents("app/Vistas/head.html");
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
							$this->mostrarPerfil($idUsuario);
						break;
					case 'configurar':
							$this->configuraPerfil($idUsuario);
						break;
					case 'sesion':
							$this->muestraFormulario(1);
						break;
					case 'registro':
							$this->muestraFormulario(2);
						break;
					case 'recuperar':
							$this->muestraFormulario(3);
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
					case 'actualizaPerfil':
							$this->actualizaPerfil();
						break;
					case 'registraCurso':
							$this->registraCurso();
						break;
					default:
							require('404.html');
						break;
				}
			}else{
				require('404.html');
			}
		}

		/**
		* Método que muestra el perfil público del usuario indicado.
		* @param int $id ID correspondiente al usuario consultado
		*
		*/
		private function mostrarPerfil($id){
			require('app/Modelo/usuarioMdl.php');
			require('app/Modelo/cursosMdl.php');
			$this->modelo = new UsuarioMdl($this->mysql);
			$cursoModelo = new CursosMdl($this->mysql);

			if(isset($_SESSION) && !empty($_SESSION)){
				if($id >= 0){
					$vista = file_get_contents("app/Vistas/perfilPublico.html");
					//$footer
					$resultado = $this->modelo->consultaPerfil($id);

					$diccionarioUsuario = array(
						'{correoUsuario}'   => $resultado['vchCorreo'],
						'{nombreUsuario}'   => $resultado['vchNombre'],
						'{ocupacionUsuario}'=> $resultado['vchOcupacion'],
						'{cumpleUsuario}'   => $resultado['dfechaNacimiento']);

					$vista = strtr($vista,$diccionarioUsuario);

					$inicioFila = strrpos($vista,'<!--{iniciaCurso}-->');
					$finalFila = strrpos($vista,'<!--{terminaCurso}-->')+21;

					$fila = substr($vista,$inicioFila,$finalFila-$inicioFila);
					$filas = "";

					$listaCursos = $cursoModelo->getMisCursos($_SESSION['idUsuario']);

					$i = 0;

					foreach ($listaCursos as $row) {
						$newFila = $fila;

						$diccionario = array(
							'{idcursourl}'=>$listaCursos[$i]['iidCurso'],
							'{colorRandom}'=>'naranja',
							'{Titulo}'=>$cursoModelo->traerCursos($listaCursos[$i]['iidCurso'])['vchNombre'],
							'{tituloPagina}'=>"Perfil");

						$newFila = strtr($newFila, $diccionario);
						$filas .= $newFila;
						$i++;
					}
					$this->head = str_replace('{tituloPagina}','Perfil', $this->head);
					$vista = str_replace($fila,$filas, $vista);
					//$this->head = strtr($this->head,$diccionario);
					$vista = $this->head . $this->header . $vista . $this->footer;

					echo $vista;
				}else{
					require('404.html');
				}
			}
		}

		private function configuraPerfil($id){

			if(isset($_SESSION) && !empty($_SESSION)){
				$vista = file_get_contents('app/Vistas/configurarPerfil.html');

				if($id >= 0){
					$diccionario = array(
						'{tituloPagina}'=>"Configurar Perfil",
						'{nombreUsuario}'=>$_SESSION['nombre'],
						'{correo}'=>$_SESSION['correo'],
						'{ocupacion}' => $_SESSION['ocupacion'],
						'<!--{descripcion}-->' => $_SESSION['descripcion'],
						'{fechaNacimiento}' => $_SESSION['fechaNacimiento']);

					$this->head = strtr($this->head,$diccionario);

					if(isset($_SESSION)){
						if(strcmp($_SESSION['sexo'],"Femenino") === 0){
							$vista = str_replace('{femeninoSelect}', 'selected="selected"', $vista);
							$vista = str_replace('{masculinoSelect}', '', $vista);
						}elseif (strcmp($_SESSION['sexo'], "Masculino")===0) {
							$vista = str_replace('{masculinoSelect}', 'selected="selected"', $vista);
							$vista = str_replace('{femeninoSelect}', '', $vista);
						}else{
							$vista = str_replace('{masculinoSelect}', '', $vista);
							$vista = str_replace('{femeninoSelect}', '', $vista);
						}
					}

					$vista = strtr($vista,$diccionario);
					$vista = $this->head . $this->header . $vista . $this->footer;

					echo $vista;
				}else{
					require('404.html');
				}
			}else{
				//error no hay sesion iniciada
			}

		}

		/**
		* Muestra un formulario indicado por el parámetro tipo
		* @param $tipo El valor 1 abre el formulario de sesion, el valor 2 el formulario de registro, 3 recuperar contraseña
		*/
		private function muestraFormulario($tipo){
			$diccionario = "";
			switch ($tipo) {
				case 1:
					$vista = file_get_contents('app/Vistas/sesion.html');
					$diccionario = array(
						'{tituloPagina}'=> "Iniciar Sesión",
						'<!--{masLinks}-->'=> '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css"/>');
					break;
				case 2:
					$vista = file_get_contents('app/Vistas/registro.html');
					$diccionario = array(
					'{tituloPagina}'=>"Registrarse",
					'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
					break;
				case 3:
					$vista = file_get_contents('app/Vistas/recuperar.html');
					$diccionario = array(
					'{tituloPagina}'=>"Recuperar contraseña");
					break;
				default:
					# code...
					break;
			}

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
						require('correos/confirmarRegistro.php');
						if($exito == false){
							$this->mostrarProblemaRegistro("No se pudo enviar el correo");
						}else{
							$vista = file_get_contents("app/Vistas/home.html");
							$diccionario = array(
							'{tituloPagina}'=>"Inicio",
							'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
							$this->head = strtr($this->head,$diccionario);
							$vista = $this->head . $this->header . $vista . $this->footer;
							echo $vista;
						}
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
			require('app/Modelo/usuarioMdl.php');
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
				if(!empty($resultado)){

					$_SESSION['correo'] = $correo;
					$_SESSION['contrasena'] = $contrasena;
					$_SESSION['nombre'] = $resultado['vchNombre'];
					$_SESSION['idUsuario'] = $resultado['iidUsuario'];					
					$_SESSION['ocupacion'] = $resultado['vchOcupacion'];
					$_SESSION['fechaNacimiento'] = $resultado['dfechaNacimiento'];
					$_SESSION['descripcion'] = $resultado['vchdescripcion'];
					$_SESSION['sexo'] = $resultado['vchSexo'];
					$_SESSION['rutaFoto'] = $resultado['bRutaFoto'];


					$this->header = $this->generalctl->headerSesion($this->headerOriginal);
					$vista = file_get_contents("app/Vistas/home.html");
					$diccionario = array(
					'{tituloPagina}'=>"Inicio",
					'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
					$this->head = strtr($this->head,$diccionario);
					$vista = $this->head . $this->header . $vista . $this->footer;
					echo $vista;
				}else{
					$this->mostrarProblemaIniciosesion("El usuario y/o contraseña es incorrecto. Intente de nuevo.");
				}
			}
		}

		private function cerrarSesion(){
			if(isset($_SESSION)){
				session_unset();
				session_destroy();
				setcookie(session_name(), '', time()-3600);

				$vista = file_get_contents("app/Vistas/sesion.html");

				$diccionario = array(
					'{tituloPagina}'=>"Inicio",
					'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
				$this->head = strtr($this->head,$diccionario);
				$this->header = $this->generalctl->headerSesion($this->headerOriginal);
				echo $this->head . $this->header . $vista . $this->footer;
			}else{
				//No hay sesión iniciada
			}
		}

		private function actualizaPerfil(){
			require('app/Modelo/usuarioMdl.php');
			$this->modelo = new UsuarioMdl($this->mysql);//se le manda la variable con la conexion establecida
			if(empty($_POST) || empty($_SESSION)){
				$this->mostrarProblemaRegistro("Favor de llenar los campos requeridos");
			}else{
				$nacimiento = $_POST["nacimiento"];
				$sexo	= $_POST["sexo"];
				$ocupacion = $_POST["ocupacion"];
				$descripcion = $_POST["descripcion"];
				$correo = $_SESSION['correo'];

				//Revisa en la BD si el correo ya existe
				if($this->modelo->existecorreo($correo)){					
					$resultado = $this->modelo->actualiza($nacimiento, $sexo, $ocupacion, $descripcion, $_SESSION['idUsuario']);//damos de alta en la BD
					if($resultado!==FALSE){//Si se pudo insertar muestra la vista											
						$this->configuraPerfil($_SESSION['idUsuario']);
							/*$vista = file_get_contents("app/Vistas/configuraPerfil.html");
							$diccionario = array(
							'{tituloPagina}'=>"Configurar Perfil");
							$this->head = strtr($this->head,$diccionario);
							$vista = $this->head . $this->header . $vista . $this->footer;
							echo $vista;*/
						
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

		private function registraCurso(){
			if(isset($_SESSION) && !empty($_SESSION) && isset($_GET['idcurso'])){
				require('app/Modelo/usuarioMdl.php');
				$this->modelo = new UsuarioMdl($this->mysql);

				//if($this->modelo->existeCUrso($_GET['idcurso']))
				$resultado = $this->modelo->registraCursoUsuario($_SESSION['idUsuario'],$_GET['idcurso']);
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
						
					}
			}else{

			}
		}


		/* Método para mostrar errores o problemas con la información recibida
		 * @param $string, cadena con el texto a mostrar en la vista. */
		private function mostrarProblemaRegistro($string){
			$vista = file_get_contents('app/Vistas/registro.html');
			$diccionario = array(
			'{tituloPagina}'=>"Registrarse",
			'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
			$diccionarioProblema = array('<!-- Problema -->'=>'<span class="text-danger">'.$string.'</span>');
			$vista = strtr($vista,$diccionarioProblema);
			$this->head = strtr($this->head, $diccionario);
			$vista = $this->head . $this->header . $vista . $this->footer;
			echo $vista;
		}

		/* Método para mostrar errores o problemas con la información recibida
		 * @param $string, cadena con el texto a mostrar en la vista. */
		private function mostrarProblemaIniciosesion($string){
			$vista = file_get_contents('app/Vistas/sesion.html');
			$diccionario = array(
			'{tituloPagina}'=>"Iniciar sesión",
			'<!--{masLinks}-->' => '<link rel="stylesheet" type="text/css" href="recursos/js/social/bootstrap-social.css">');
			$diccionarioProblema = array('<!-- Problema -->'=>'<span class="text-danger">'.$string.'</span>');
			$vista = strtr($vista,$diccionarioProblema);
			$this->head = strtr($this->head, $diccionario);
			$vista = $this->head . $this->header . $vista . $this->footer;
			echo $vista;
		}
	}
?>
