<!DOCTYPE html>
<html>
	<head>
		<title>Resultado del examen</title>
		<link rel="shortcut icon" href="../../recursos/img/logoTieOut.ico" type="image/x-icon" /> 	
		<link rel="stylesheet" type="text/css" href="../../recursos/css/repertorio.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php include_once("imports.php"); ?>
	</head>
	<body>
		<?php  include_once("header.php"); ?>
		<div class="content">
			<section class="resultado" >
				<h3 class="text-center encabezado">Tu calificación es:</h3>
				<h1 class="text-center">78</h1>
			</section>
			<section id="recomendaciones" style="padding:2em 3em 0em; ">
			<h3 class="text-center">Otros cursos disponibles</h3>
			<div class="row" style="padding:0 1em 0;">
				<div class="col-md-4 naranja" style="background-color: #957FD4; height: 15em; padding-left: 2em;" >
					<article>					
						<h3>Árboles</h3>
						<p>Un árbol es una estructura no lineal en la que cada nodo puede apuntar a uno o varios nodos. También se suele dar una definición...</p>
						<a class="btn btn-default" href="curso1.php" role="button">Ver más...</a>
					</article>
				</div>
				<div class="col-md-4 azul" style="background-color: #FF9D8D; height: 15em; padding-left: 2em;" >
					<article>					
						<h3>Árboles</h3>
						<p>Un árbol es una estructura no lineal en la que cada nodo puede apuntar a uno o varios nodos. También se suele dar una definición...</p>
						<a class="btn btn-default" href="curso1.php" role="button">Ver más...</a>
					</article>
				</div>
				<div class="col-md-4 cafe" style="background-color: #4AC181; height: 15em; padding-left: 2em;" >
					<article>					
						<h3>Árboles</h3>
						<p>Un árbol es una estructura no lineal en la que cada nodo puede apuntar a uno o varios nodos. También se suele dar una definición...</p>
						<a class="btn btn-default" href="curso1.php" role="button">Ver más...</a>
					</article>
				</div>
			</div>
		</section>
		
		</div>
		<?php include_once('footer.php'); ?>
	</body>
</html>