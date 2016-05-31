<?php

	session_start();
	require('Modelo/singleton.php');
	$bandera = false;
	if(isset($_GET['controlador'])){
		switch ($_GET['controlador']) {
			case 'usuarios':
					require('Controladores/usuariosCtl.php');
					$controlador = new Usuario();
					$bandera = true;
				break;
			case 'cursos':
					require('Controladores/cursosCtl.php');
					$controlador = new Curso();
					$bandera = true;
				break;
			case 'examenes':
					require('Controladores/examenCtl.php');
					$controlador = new Examen();
					$bandera = true;
				break;
			default:
					//require('app/Controladores/generalCtl.php');
					//$generalctl = new General();
					$head = file_get_contents('Vistas/head.html');
					$vista = file_get_contents('Vistas/home.html');
					$footer = file_get_contents('Vistas/footer.html');

					$diccionario = array('{tituloPagina}'=>"Inicio");
					$head = strtr($head, $diccionario);
					$vista = $head . $vista . $footer;
					echo $vista;
				break;
		}

		if($bandera){
			$controlador->ejecutar();
		}
	}else{
		$head = file_get_contents('Vistas/head.html');
		$vista = file_get_contents('Vistas/home.html');
		$footer = file_get_contents('Vistas/footer.html');

		$diccionario = array('{tituloPagina}'=>"Inicio");
		$head = strtr($head, $diccionario);
		$vista = $head . $vista . $footer;
		echo $vista;
	}
?>
