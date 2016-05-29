<?php
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


			default:
				# code...
					require_once('Vistas/home.html');
				break;
		}

		if($bandera){
			$controlador->ejecutar();
		}
	}else{
		require_once('Vistas/home.html');
	}
?>
