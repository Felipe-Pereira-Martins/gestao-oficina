<?php 

require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];

$html = file_get_contents($url."painel-mecanico/rel/rel_os_html.php?id=$id");
echo $html;


?>