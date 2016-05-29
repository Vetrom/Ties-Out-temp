<?php
	$bandera = false;
	if(isset($_GET['controlador'])){
		switch ($_GET['controlador']) {
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
