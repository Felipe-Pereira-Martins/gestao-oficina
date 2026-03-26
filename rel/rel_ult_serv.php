<?php 

require_once("../conexao.php"); 
@session_start();

$id = $_GET['id'];

$html = file_get_contents($url."rel/rel_ult_serv_html.php?id=$id");
echo $html;


?>