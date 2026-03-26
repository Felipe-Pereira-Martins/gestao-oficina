<?php 

require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];
$email = @$_GET['email'];

$html = file_get_contents($url."painel-mecanico/rel/rel_orcamento_html.php?id=$id");
echo $html;

//ENVIAR O ORÇAMENTO PARA O EMAIL DO CLIENTE

if($email != ""){
	$destinatario = $email;
	$assunto = $nome_oficina . ' - Orçamento';;
	$mensagem = $html;
	$cabecalhos = "From: " . $email_adm. "\r\n" ."Content-type: text/html; charset=utf-8; ";
	@mail($destinatario, $assunto, $mensagem, $cabecalhos);
}
?>