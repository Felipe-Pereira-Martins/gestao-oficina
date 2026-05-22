<?php
/**
 * ============================================================
 * EDITAR PERFIL DO USUÁRIO — PAINEL RECEPÇÃO
 * Atualiza os dados do usuário logado no sistema
 * ============================================================
 */

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

/**
 * Captura os dados enviados pelo formulário de edição de perfil
 * via método POST (AJAX)
 */

// Dados principais do usuário
$nome  = $_POST['nome_usu'];
$cpf   = $_POST['cpf_usu'];
$email = $_POST['email_usu'];
$senha = $_POST['senha_usu'];

// CPF antigo (para verificar se houve alteração)
$antigo = $_POST['antigo_usu'];

// ID do usuário logado (vindo de campo oculto)
$id = $_POST['id_usu'];

/**
 * ============================================================
 * VALIDAÇÕES DOS CAMPOS OBRIGATÓRIOS
 * ============================================================
 */

// Verifica se o campo nome foi preenchido
if ($nome == "") {
    echo 'O nome é Obrigatório!';
    exit();
}

// Verifica se o campo email foi preenchido
if ($email == "") {
    echo 'O email é Obrigatório!';
    exit();
}

// Verifica se o campo CPF foi preenchido
if ($cpf == "") {
    echo 'O CPF é Obrigatório!';
    exit();
}

/**
 * ============================================================
 * VERIFICA DUPLICIDADE DE CPF
 * Só verifica se o CPF foi alterado (diferente do antigo)
 * ============================================================
 */

if ($antigo != $cpf) {
    // Busca se já existe outro usuário com o novo CPF
    $query     = $pdo->query("SELECT * FROM usuarios where cpf = '$cpf' ");
    $res       = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);

    // Se encontrou algum registro, CPF já está em uso
    if ($total_reg > 0) {
        echo 'O CPF já está Cadastrado!';
        exit();
    }
}

/**
 * ============================================================
 * ATUALIZA OS DADOS DO USUÁRIO NO BANCO
 * Utiliza prepared statement para segurança
 * ============================================================
 */

// Prepara a query de atualização com bind de parâmetros
$res2 = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email, senha = :senha WHERE id = '$id'");

// Vincula os valores aos parâmetros nomeados
$res2->bindValue(":nome", $nome);
$res2->bindValue(":cpf", $cpf);
$res2->bindValue(":email", $email);
$res2->bindValue(":senha", $senha);

// Executa a atualização
$res2->execute();

// Retorna mensagem de sucesso (capturada pelo AJAX)
echo 'Salvo com Sucesso!';

?>