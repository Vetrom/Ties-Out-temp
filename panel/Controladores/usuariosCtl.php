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
			session_start();
			require('app/Modelo/singleton.php');
			require('app/Controladores/generalCtl.php');

			$this->instancia = Conexion::getInstance();
			$this->instancia->__construct();

			$this->mysql = $this->instancia->getConnection();

			$this->generalctl = new General();
+
+			$this->header = file_get_contents("app/Vistas/header.html");
+			$this->header = $this->generalctl->headerSesion($this->header);
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
					default:
							require('404.php');
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
			/*Conecta al modelo correspondiente para consultar con el ID al usuario*/
			//require('app/Vistas/perfilPublico.html');

			if($id >= 0){
				$vista = file_get_contents("app/Vistas/perfilPublico.html");
				//$footer

				$diccionarioUsuario = array(
					'{correoUsuario}'=>'dancaballeroc@gmail.com',
					'{nombreUsuario}'=>'Alfonso Caballero');

				$vista = strtr($vista,$diccionarioUsuario);


				$listaTitulos = array(
					'Arbol',
					'Algoritmos',
					'otro');

				$listaUrl = array(
					'app/Vistas/curso1.php',
					'app/Vistas/curso2.php',
					'app/Vistas/curso2.php');

				$inicioFila = strrpos($vista,'<!--{iniciaCurso}-->');
				$finalFila = strrpos($vista,'<!--{terminaCurso}-->')+21;

				$fila = substr($vista,$inicioFila,$finalFila-$inicioFila);
				$filas = "";

				$i = 0;

				foreach ($listaTitulos as $row) {
					$newFila = $fila;

					$diccionario = array(
						'{urlCurso}'=>$listaUrl[$i],
						'{colorRandom}'=>'naranja',
						'{Titulo}'=>$row,
						'{tituloPagina}'=>"Perfil");

					$newFila = strtr($newFila, $diccionario);
					$filas .= $newFila;
					$i++;
				}

				$vista = str_replace($fila,$filas, $vista);
				$this->head = strtr($this->head,$diccionario);
				$vista = $this->head . $this->header . $vista . $this->footer;

				echo $vista;
			}else{
				require('404.html');
			}
		}

		private function configuraPerfil($id){
			$vista = file_get_contents('app/Vistas/configurarPerfil.html');

			if($id >= 0){
				$diccionario = array(
					'{tituloPagina}'=>"Configurar Perfil");

				$this->head = strtr($this->head,$diccionario);
				$vista = $this->head . $this->header . $vista . $this->footer;

				echo $vista;
			}else{
				require('404.html');
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
+					$_SESSION['contrasena'] = $contrasena;
+					$_SESSION['nombre'] = $resultado['vchnombre'];
+
+					$this->header = $this->generalctl->headerSesion($this->header);
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
