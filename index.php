<?php
	
	session_start();
	require('app/Modelo/singleton.php');
	$bandera = false;
	if(isset($_GET['controlador'])){
		switch ($_GET['controlador']) {
			case 'busqueda':
					require('app/Controladores/busquedaCtl.php');
					$controlador = new Busqueda();
					$bandera = true;
				break;
			case 'cursos':
					require('app/Controladores/cursosCtl.php');
					$controlador = new Curso();
					$bandera = true;
				break;
			case 'general':
					require('app/Controladores/generalCtl.php');
					$controlador = new General();
					$bandera = true;
				break;
			case 'usuarios':
					require('app/Controladores/usuariosCtl.php');
					$controlador = new Usuario();
					$bandera = true;
				break;
			default:
			require('app/Controladores/generalCtl.php');
					$generalctl = new General();

					$head = file_get_contents('app/Vistas/head.html');
					$header = file_get_contents('app/Vistas/header.html');
					$header = $generalctl->headerSesion($header);

					$vista = file_get_contents('app/Vistas/home.html');
					$footer = file_get_contents('app/Vistas/footer.html');

					$diccionario = array('{tituloPagina}'=>"Inicio");

					$head = strtr($head, $diccionario);

							$inicioCurso = strrpos($vista, '<!--{inicioMasVistos}-->');
							$finCurso    = strrpos($vista, '<!--{finMasVistos}-->') + 21;
							$curso       = substr($vista, $inicioCurso, $finCurso - $inicioCurso);
							//$listaCursos = Curso::
							$cursosGrid = "";

							for ($i=0; $i < 9; $i++) { 
								$newCurso = $curso;

								$cursosGrid .= $newCurso;			
							}
							
							$vista = str_replace($curso, $cursosGrid, $vista);
					
					$vista = $head . $header . $vista . $footer;
					echo $vista;
				break;
		}

		if($bandera){
			$controlador->ejecutar();
		}
	}else{
		require('app/Controladores/generalCtl.php');
		require('app/Controladores/cursosCtl.php');
		$generalctl = new General();

		$head = file_get_contents('app/Vistas/head.html');
		$header = file_get_contents('app/Vistas/header.html');
		$header = $generalctl->headerSesion($header);

		$vista = file_get_contents('app/Vistas/home.html');
		$footer = file_get_contents('app/Vistas/footer.html');

		$diccionario = array('{tituloPagina}'=>"Inicio");

		$head = strtr($head, $diccionario);

		$inicioCurso = strrpos($vista, '<!--{inicioMasVistos}-->');
		$finCurso    = strrpos($vista, '<!--{finMasVistos}-->') + 21;
		$curso       = substr($vista, $inicioCurso, $finCurso - $inicioCurso);
		//$listaCursos = Curso::
		$cursosGrid = "";

		for ($i=0; $i < 9; $i++) { 
			$newCurso = $curso;

			$cursosGrid .= $newCurso;			
		}
		
		$vista = str_replace($curso, $cursosGrid, $vista);
		$vista = $head . $header . $vista . $footer;
		echo $vista;
	}
?>
