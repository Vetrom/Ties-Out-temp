<html>
	<head>
		<title>Métodos de Ordenamiento</title>
		<?php include_once("imports.php"); ?>
		<link rel="stylesheet" type="" href="<?php echo ROOTPATH ?>/recursos/css/cursos.css">
		<link rel="shortcut icon" href="<?php echo ROOTPATH ?>/recursos/img/logoTieOut.ico" type="image/x-icon" /> 
	</head>

	<body>
		<?php include_once('header.php'); ?>
		<?php include_once('menuCurso2.php'); ?>

		<div class="content2">
			<section class = "encabezado">
				<h2>MÉTODOS DE ORDENAMIENTO</h2><br />				
			</section>
			<section id="clasificacion" class="contenido">
				<h3>Clasificación</h3>
				<p align="justify">
					Los algoritmos de ordenamiento se pueden clasificar en las siguientes maneras:
				</p>
				<ul id = "lista">
					<li align="justify">La más común es clasificar según el lugar donde se realice la ordenación
						<ul>
							<li align="justify"><b>Algoritmos de ordenamiento interno:</b> en la memoria del ordenador.</li>
							<li align="justify"><b>Algoritmos de ordenamiento externo:</b> en un lugar externo como un disco duro.</li>
						</ul>
					</li>
					<li align="justify">Por el tiempo que tardan en realizar la ordenación, dadas entradas ya ordenadas 
						o inversamente ordenadas:
						<ul>
							<li align="justify"><b>Algoritmos de ordenación natural:</b> Tarda lo mínimo posible cuando la entrada 
							está ordenada.</li>
							<li align="justify"><b>Algoritmos de ordenación no natural:</b> Tarda lo mínimo posible cuando la entrada 
							está inversamente ordenada.</li>
						</ul>
					</li>
				</ul>
				<p align="justify">
					Por estabilidad: un ordenamiento estable mantiene el orden relativo que tenían originalmente los 
					elementos con claves iguales. Por ejemplo, si una lista ordenada por fecha se reordena en orden 
					alfabético con un algoritmo estable, todos los elementos cuya clave alfabética sea la misma 
					quedarán en orden de fecha. Otro caso sería cuando no interesan las mayúsculas y minúsculas, pero 
					se quiere que si una clave aBC estaba antes que AbC, en el resultado ambas claves aparezcan juntas 
					y en el orden original: aBC, AbC.
				</p>
				<p  align="justify">
					Cuando los elementos son indistinguibles (porque cada elemento se ordena por la clave completa) 
					la estabilidad no interesa. Los algoritmos de ordenamiento que no son estables se pueden implementar 
					para que sí lo sean. Una manera de hacer esto es modificar artificialmente la clave de ordenamiento 
					de modo que la posición original en la lista participe del ordenamiento en caso de coincidencia.
				</p>

				<p align="justify">Los algoritmos se distinguen por las siguientes características:</p>	
				<ul>
					<li align="justify">Complejidad computacional (peor caso, caso promedio y mejor caso) en términos 
					de n, el tamaño de la lista o arreglo. Para esto se usa el concepto de orden de una función y se 
					usa la notación O(n). El mejor comportamiento para ordenar (si no se aprovecha la estructura de 
					las claves) es O(n log n). Los algoritmos más simples son cuadráticos, es decir O(n²). Los algoritmos 
					que aprovechan la estructura de las claves de ordenamiento (p. ej. bucket sort) pueden ordenar en 
					O(kn) donde k es el tamaño del espacio de claves. Como dicho tamaño es conocido a priori, se puede 
					decir que estos algoritmos tienen un desempeño lineal, es decir O(n).</li>
					<li align="justify">Uso de memoria y otros recursos computacionales. También se usa la notación O(n).</li>
				</ul>
			</section>
			
			<div class="row navegar">
				<div class="col-lg-12">
					<ul class="pager">
						<li class="previous">
							<a href="curso2.php">
								<i class="glyphicon glyphicon-arrow-left"></i>
								Anterior
							</a>
						</li>
						<li class="next">
							<a href="curso2estabilidad.php">
								Siguiente
								<i class="glyphicon glyphicon-arrow-right"></i>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<?php include_once('footerTips.php'); ?>	
		</div>
		<?php include_once('footer.php'); ?>
	</body>
</html>