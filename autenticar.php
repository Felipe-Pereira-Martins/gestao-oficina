<?php
require_once("conexao.php"); // Inclui conexão com banco de dados e configurações
session_start(); // Inicia sessã
// Captura os dados do formulário
$email = trim($_POST['email'] ?? ''); // Email com espaços removidos
$senha = trim($_POST['senha'] ?? ''); // Senha com espaços removidos
// Verifica se os campos vieram preenchidos
if (empty($email) || empty($senha)) { // Se algum campo estiver vazio
    echo "<script>alert('Preencha todos os campos!')</script>"; // Alerta
    echo "<script>window.location='index.php'</script>"; // Redireciona
    exit; // Interrompe execução
}
// Busca usuário pelo email
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1"); // Prepara query segura
$stmt->bindValue(":email", $email); // Vincula parâmetro
$stmt->execute(); // Executa consulta
// Pega o usuário
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Busca resultado como array associativo
// ===============================
// LOGIN
// ===============================
if ($user) { // Se usuário foi encontrado
    // =========================================
    // SENHA EM TEXTO PURO (SEU CASO ATUAL)
    // =========================================
    if ($senha == $user['senha']) { // Compara senha em texto puro
        // Segurança
        session_regenerate_id(true); // Regenera ID da sessão por segurança
        // Sessões
        $_SESSION['id_usuario']    = $user['id']; // Armazena ID do usuário
        $_SESSION['nome_usuario']  = $user['nome']; // Armazena nome
        $_SESSION['cpf_usuario']   = $user['cpf']; // Armazena CPF
        $_SESSION['nivel_usuario'] = $user['nivel']; // Armazena nível de acesso
        $nivel = $user['nivel']; // Pega nível do usuário
        // Redirecionamentos
        if ($nivel == 'admin') { // Se for administrador
            header("Location: painel-adm"); // Redireciona para painel admin
            exit;
        }
        if ($nivel == 'mecanico') { // Se for mecânico
            header("Location: painel-mecanico"); // Redireciona para painel mecânico
            exit;
        }
        if ($nivel == 'recep') { // Se for recepcionista
            header("Location: painel-recepcao"); // Redireciona para painel recepção
            exit;
        }
        // Caso não encontre nível
        echo "<script>alert('Nível de usuário inválido!')</script>"; // Alerta nível inválido
        echo "<script>window.location='index.php'</script>"; // Redireciona
        exit;
    } else { // Senha incorreta
        echo "<script>alert('Senha Incorreta!')</script>"; // Alerta senha errada
        echo "<script>window.location='index.php'</script>"; // Redireciona
        exit;
    }
} else { // Usuário não encontrado
    echo "<script>alert('Usuário não encontrado!')</script>"; // Alerta usuário não existe
    echo "<script>window.location='index.php'</script>"; // Redireciona
    exit;
}
