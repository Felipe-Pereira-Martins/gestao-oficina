<?php
// ============================================================
//  GESTÃO DE OFICINA — index.php
// ============================================================
require_once("conexao.php"); // Inclui conexão com banco de dados e configurações
// Cria admin padrão se não existir
$q = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE nivel = 'admin'"); // Conta usuários admin
if ((int)$q->fetchColumn() === 0) { // Se não existir admin
    $pdo->query("INSERT INTO usuarios SET
        nome  = 'Administrador',
        cpf   = '000.000.000-00',
        email = '$email_adm',
        senha = '123',
        nivel = 'admin'
    "); // Cria usuário administrador padrão
}

// Remove orçamentos antigos
$data_limite = date('Y-m-d', strtotime("-$excluir_orcamento_dias days")); // Calcula data limite para exclusão
$q = $pdo->query("SELECT id FROM orcamentos WHERE data <= '$data_limite'"); // Busca orçamentos vencidos
foreach ($q->fetchAll(PDO::FETCH_COLUMN) as $id) { // Para cada orçamento vencido
    $pdo->query("DELETE FROM orcamentos WHERE id = '$id'"); // Exclui orçamento
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Oficina — Acesso</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- CSS do sistema (carregado POR ÚLTIMO para sobrescrever Bootstrap) -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"> <!-- Ícone da página -->
</head>

<body class="login-page"> <!-- Classe específica para página de login -->

<!--
    .login-wrapper usa position:fixed; inset:0
    Isso torna o layout completamente imune ao Bootstrap —
    não importa o que o Bootstrap faça no html/body.
-->
<div class="login-wrapper"> <!-- Container principal do layout de login -->
    <!-- ── PAINEL ESQUERDO — Visual / Branding ── -->
    <aside class="login-visual" aria-hidden="true"> <!-- Painel decorativo esquerdo -->

        <div class="visual-accent-line"></div> <!-- Linha decorativa -->
        <div class="visual-watermark">RPM</div> <!-- Marca d'água de fundo -->

        <div class="visual-content">
            <p class="visual-tagline">
                <i class="fas fa-circle" style="font-size:5px;"></i>
                Gestão Automotiva
            </p>

            <h2 class="visual-headline">
                Controle total<br>
                da sua <em>oficina</em>.
            </h2>

            <p class="visual-desc">
                Gerencie orçamentos, ordens de serviço, clientes e
                estoque em um único painel — rápido, seguro e sob
                medida para oficinas de alta performance.
            </p>

            <div class="visual-stats"> <!-- Indicadores visuais -->
                <div class="stat-item">
                    <span class="stat-value">360<span>°</span></span>
                    <span class="stat-label">Visibilidade</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">24<span>/7</span></span>
                    <span class="stat-label">Disponível</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100<span>%</span></span>
                    <span class="stat-label">Em Nuvem</span>
                </div>
            </div>
        </div>

    </aside>

    <!-- Divisória -->
    <div class="login-divider" aria-hidden="true"></div> <!-- Linha divisória entre painéis -->

    <!-- ── PAINEL DIREITO — Formulário ── -->
    <main class="login-form-panel"> <!-- Painel do formulário de login -->
        <div class="form-inner">

            <!-- Logo -->
            <div class="form-logo">
                <img src="img/logo.png" alt="Gestão de Oficina">
            </div>

            <!-- Título -->
            <div class="form-heading">
                <span class="label-pill">
                    <i class="fas fa-lock"></i>
                    Área restrita
                </span>
                <h1>Acesso ao Sistema</h1>
                <p>Informe suas credenciais para continuar.</p>
            </div>

            <!-- Formulário -->
            <form method="POST" action="autenticar.php" novalidate> <!-- Envia para autenticação -->

                <!-- E-mail -->
                <div class="field-group">
                    <div class="field-label">
                        <label for="login_email">E-mail</label>
                    </div>
                    <div class="field-wrap">
                        <input type="email"
                               id="login_email"
                               name="email"
                               class="form-control-custom"
                               placeholder="seu@email.com"
                               required
                               autofocus
                               autocomplete="email">
                        <span class="field-icon" aria-hidden="true">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <!-- Senha -->
                <div class="field-group">
                    <div class="field-label">
                        <label for="login_senha">Senha</label>
                    </div>
                    <div class="field-wrap">
                        <input type="password"
                               id="login_senha"
                               name="senha"
                               class="form-control-custom"
                               placeholder="••••••••"
                               required
                               autocomplete="current-password">
                        <span class="field-icon" aria-hidden="true">
                            <i class="fas fa-key"></i>
                        </span>
                        <button type="button"
                                class="toggle-pw"
                                id="togglePw"
                                aria-label="Mostrar senha">
                            <i class="fas fa-eye" id="togglePwIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    <i class="fas fa-arrow-right btn-icon"></i>
                    Entrar
                </button>

            </form>

            <!-- Recuperar senha -->
            <div class="form-footer">
                <a href="#"
                   class="recover-link"
                   data-toggle="modal"
                   data-target="#modalRecuperar"> <!-- Abre modal de recuperação -->
                    <i class="fas fa-undo-alt"></i>
                    Recuperar senha
                </a>
            </div>

            <!-- Rodapé -->
            <div class="form-brand-footer">
                <span class="powered">Martins Sistemas</span>
                <span class="version">v2026</span>
            </div>

        </div>
    </main>

</div><!-- /.login-wrapper -->


<!-- ── MODAL: Recuperação de Senha ── -->
<div class="modal fade"
     id="modalRecuperar"
     data-backdrop="static" <!-- Não fecha ao clicar fora -->
     tabindex="-1"
     role="dialog"
     aria-labelledby="tituloModalRecuperar">

    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Modal centralizado -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalRecuperar">
                    <i class="fas fa-shield-alt"></i>
                    Recuperar Senha
                </h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="form-recuperar" novalidate> <!-- Formulário de recuperação -->
                <div class="modal-body">
                    <div class="field-group">
                        <div class="field-label">
                            <label for="email_recuperar">E-mail cadastrado</label>
                        </div>
                        <div class="field-wrap">
                            <input type="email"
                                   id="email_recuperar"
                                   name="email"
                                   class="form-control-custom"
                                   placeholder="seu@email.com"
                                   required>
                            <span class="field-icon" aria-hidden="true">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div id="mensagem" role="alert" aria-live="polite"></div> <!-- Área para feedback -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn-recover">
                        <i class="fas fa-paper-plane mr-1"></i>Enviar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> <!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> <!-- Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->

<script>
// Toggle senha
(function () {
    var btn   = document.getElementById('togglePw'); // Botão de mostrar/ocultar
    var input = document.getElementById('login_senha'); // Campo de senha
    var icon  = document.getElementById('togglePwIcon'); // Ícone do botão
    if (!btn) return; // Se botão não existe, sai
    btn.addEventListener('click', function () {
        var isPw = input.type === 'password'; // Verifica se é campo de senha
        input.type = isPw ? 'text' : 'password'; // Alterna entre texto e senha
        icon.className = isPw ? 'fas fa-eye-slash' : 'fas fa-eye'; // Alterna ícone
        btn.setAttribute('aria-label', isPw ? 'Ocultar senha' : 'Mostrar senha'); // Atualiza acessibilidade
    });
}());
</script>

<script src="js/login.js"></script> <!-- Script do login -->
</body>
</html>