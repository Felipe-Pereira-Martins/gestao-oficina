<?php
// Inclui o arquivo de conexão com o banco de dados
require_once("conexao.php");

// ============================================
// CRIAÇÃO AUTOMÁTICA DO USUÁRIO ADMINISTRADOR
// ============================================
// Verifica se já existe um usuário com nível 'admin' no sistema
$query = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'admin'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);

// Se não existir nenhum administrador (total_reg == 0), cria um automaticamente
if($total_reg == 0){
    // Insere o usuário admin padrão com email vindo da variável $email_adm (definida no conexao.php)
    // Senha padrão: 123 (recomendado alterar após primeiro acesso)
    $res = $pdo->query("INSERT INTO usuarios SET nome = 'Administrador', cpf = '000.000.000-00', email = '$email_adm', senha = '123', nivel = 'admin'");   
}

// ============================================
// LIMPEZA AUTOMÁTICA DE ORÇAMENTOS ANTIGOS
// ============================================
// Pega a data atual no formato YYYY-MM-DD
$data_hoje = date('Y-m-d');

// Calcula a data limite para exclusão (data atual - X dias)
// $excluir_orcamento_dias é uma variável definida no arquivo de configuração
$data_15 = date('Y-m-d', strtotime("-$excluir_orcamento_dias days", strtotime($data_hoje)));

// Busca todos os orçamentos com data menor ou igual à data limite (mais antigos)
$query = $pdo->query("SELECT * FROM orcamentos WHERE data <= '$data_15'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Percorre todos os orçamentos encontrados e exclui cada um
for ($i=0; $i < count($res); $i++) { 
    $id_orc = $res[$i]['id']; // Pega o ID do orçamento
    $pdo->query("DELETE FROM orcamentos WHERE id = '$id_orc'"); // Exclui o orçamento
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- Configuração de viewport para responsividade em dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Oficina - Martins Sistemas </title>
    
    <!-- Bootstrap 4.3.1 CSS - Framework para layout responsivo -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <!-- Font Awesome 5 - Biblioteca de ícones -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    
    <!-- Estilos personalizados do sistema (sobrescreve o Bootstrap quando necessário) -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Favicon - ícone que aparece na aba do navegador -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<!-- Classe 'login-page' aplicada para estilização específica da tela de login -->
<body class="login-page">
    <!-- Card principal de login - container centralizado -->
    <div class="login-card">
        <!-- Cabeçalho do card com logo -->
        <div class="login-header">
            <img src="img/logo.png" alt="SAS - Sistema de Oficina">
        </div>
        
        <!-- Corpo do card com formulário de login -->
        <div class="login-body">
            <h2>Acesso ao Sistema</h2>
            
            <!-- Formulário de login - envia dados para autenticar.php via POST -->
            <form method="post" action="autenticar.php">
                <!-- Campo de e-mail -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Digite seu e-mail" required autofocus>
                    <!-- required: campo obrigatório | autofocus: foco automático ao carregar -->
                </div>
                
                <!-- Campo de senha -->
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" 
                           placeholder="Digite sua senha" required>
                </div>
                
                <!-- Botão de submit com ícone de entrada -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                </button>
                
                <!-- Link para abrir modal de recuperação de senha -->
                <a href="#" class="recuperar-link" data-toggle="modal" data-target="#modalRecuperar">
                    <i class="fas fa-key"></i> Recuperar Senha
                </a>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL DE RECUPERAÇÃO DE SENHA               -->
    <!-- ============================================ -->
    <!-- modal fade: efeito de fade ao abrir/fechar -->
    <!-- data-backdrop="static": impede fechar clicando fora -->
    <div class="modal fade" id="modalRecuperar" data-backdrop="static" tabindex="-1" role="dialog">
        <!-- modal-dialog-centered: centraliza verticalmente -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Cabeçalho do modal -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Recuperar Senha
                    </h5>
                    <!-- Botão X para fechar -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Formulário de recuperação de senha (envio via AJAX) -->
                <form method="POST" id="form-recuperar">
                    <div class="modal-body">
                        <!-- Campo para digitar o e-mail cadastrado -->
                        <div class="form-group">
                            <label for="email-recuperar">E-mail cadastrado</label>
                            <input type="email" class="form-control" id="email-recuperar" 
                                   name="email" placeholder="Digite seu e-mail" required>
                        </div>
                        
                        <!-- Área para exibir mensagens de feedback (sucesso/erro) -->
                        <div id="mensagem"></div>
                    </div>
                    
                    <!-- Rodapé do modal com botões -->
                    <div class="modal-footer">
                        <!-- Botão para fechar o modal sem ação -->
                        <button type="button" class="btn-fechar" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Fechar
                        </button>
                        <!-- Botão para enviar o formulário de recuperação -->
                        <button type="submit" class="btn-recuperar">
                            <i class="fas fa-paper-plane mr-1"></i>Recuperar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SCRIPTS JAVASCRIPT                          -->
    <!-- ============================================ -->
    <!-- jQuery - necessário para Bootstrap e AJAX -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
    <!-- Popper.js - necessário para alguns componentes do Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    
    <!-- Bootstrap JS - para funcionalidades como modal, dropdown, etc -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <!-- Script personalizado da tela de login (contém a lógica AJAX para recuperar senha) -->
    <script src="js/login.js"></script>
</body>
</html>