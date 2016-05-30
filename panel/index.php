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
