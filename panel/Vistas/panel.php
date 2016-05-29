<html>
	<head>
		<title>Inicio</title>
		<link rel="shortcut icon" href="../img/logoTieOut.ico" type="image/x-icon" /> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<?php include_once("imports.php"); ?>
	</head>
	<body>
		<?php include_once("header.php"); ?>
		
		 <!-- aqui se manda llamar la grafica-->
    	<div id="piechart"></div>
    	<div id="chart_div"></div>
    	
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script type="text/javascript" src="<?php echo ROOTPATH ?>/js/graficas.js"></script>	
	</body>
</html>