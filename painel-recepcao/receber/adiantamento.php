<?php 
require_once("../../conexao.php"); 
@session_start();
$valor = $_POST['valor'];
$id = $_POST['txtid2'];

$pdo->query("UPDATE contas_receber SET adiantamento = '$valor' WHERE id = '$id'");


$pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', descricao = 'Adiantamento', valor = '$valor', funcionario = '$_SESSION[cpf_usuario]', data = curDate()");

echo 'Salvo com Sucesso!';

?>