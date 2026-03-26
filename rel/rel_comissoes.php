<?php 

require_once("../conexao.php"); 
@session_start();

$cpf_usuario = $_SESSION['cpf_usuario'];
$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];


$html = file_get_contents($url."rel/rel_comissoes_html.php?dataInicial=$dataInicial&dataFinal=$dataFinal&cpf=$cpf_usuario");
echo $html;


?>