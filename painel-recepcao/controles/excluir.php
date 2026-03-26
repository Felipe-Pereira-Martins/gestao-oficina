<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];

$pdo->query("DELETE FROM controles WHERE id = '$id'");

echo 'Excluído com Sucesso!';

?>