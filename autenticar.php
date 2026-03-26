<?php 
require_once("conexao.php");
session_start();

/* $email = $_POST['email'];
$senha = $_POST['senha']; */

// filter_input é uma função do PHP usada para capturar e validar dados de entrada
// Prática moderna que ajuda a tornar o código mais seguro e limpo
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';

// Código utilizando o Query, funciona com consultas rápidas porém vulnerável a SQL Injection
/* $query = $pdo->prepare("SELECT * FROM usuarios where email = :email and senha = :senha");
$query->bindValue(":senha", $senha);
$query->bindValue(":email", $email);
$query->execute(); */

// Prepara a query para buscar apenas pelo email
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1"); // LIMIT1 garante que so venha um registro ser for duplicado
$stmt->bindValue(":email", $email);
$stmt->execute();


// Pega o resultado (um único usuário)
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou usuário e se a senha confere
if($user && password_verify($senha, $user['senha'])){
    // Login válido → cria sessão
    session_regenerate_id(true);

    $_SESSION['id_usuario']   = $user['id'];
    $_SESSION['nome_usuario'] = $user['nome'];
    $_SESSION['cpf_usuario']  = $user['cpf'];
    $_SESSION['nivel_usuario']= $user['nivel'];

    $nivel = $user['nivel'];

    // Redireciona conforme nível
    if($nivel == 'admin'){
        echo "<script language='javascript'> window.location='painel-adm' </script>";
    }

    if($nivel == 'mecanico'){
        echo "<script language='javascript'> window.location='painel-mecanico' </script>";
    }

    if($nivel == 'recep'){
        echo "<script language='javascript'> window.location='painel-recepcao' </script>";
    }

} else {
    // Login inválido
    echo "<script language='javascript'> window.alert('Usuário ou Senha Incorreta!') </script>";
    echo "<script language='javascript'> window.location='index.php' </script>";    
}


?>