<?php 
require_once("conexao.php"); // Inclui conexão com banco de dados e configurações

$email = $_POST['email']; // Captura email enviado pelo formulário

if($email == ""){ // Verifica se campo email está vazio
    echo 'Preencha o Campo Email!';
    exit(); // Interrompe execução
}

$res = $pdo->query("SELECT * FROM usuarios where email = '$email' "); // Busca usuário pelo email
$dados = $res->fetchAll(PDO::FETCH_ASSOC); // Converte resultado em array associativo
if(@count($dados) > 0){ // Verifica se encontrou algum usuário (suprime warning)
    $senha = $dados[0]['senha']; // Pega senha do primeiro resultado

   //ENVIAR O EMAIL COM A SENHA
    $destinatario = $email; // Define destinatário
    $assunto = utf8_decode($nome_oficina . ' - Recuperação de Senha');; // Define assunto (converte acentos)
    $mensagem = utf8_decode('Sua senha é ' .$senha); // Define corpo do email
    $cabecalhos = "From: ".$email_adm; // Define remetente
    @mail($destinatario, $assunto, $mensagem, $cabecalhos); // Envia email (suprime erros)
    echo 'Sua senha foi Enviada para seu Email!';

}else{
    echo 'Email não Cadastrado!'; // Email não encontrado no banco
}

?>