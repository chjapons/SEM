<?php
//$nom = ucfirst($_GET["texto_consulta"]);
$nom = $_GET["texto_consulta"];

$textom = strtolower($nom);

$texto = str_replace(" ", "_", $textom);

	if(!file_exists("expmedicina.pl")) die("No se puede localizar el archivo .pl, el directorio actual es: ".__DIR__);

	//$output = `swipl -s expmedicina.pl -g $nom. -t halt.`;

	$output = `swipl -s expmedicina.pl -g $texto. -t halt.`;

	//echo utf8_decode($output);
	echo ($output);

	//echo $output;
 
 //ucfirst($string)
?>