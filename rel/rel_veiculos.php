<?php 

require_once("../conexao.php"); 
@session_start();


$html = file_get_contents($url."rel/rel_veiculos_html.php");
echo $html;


?>