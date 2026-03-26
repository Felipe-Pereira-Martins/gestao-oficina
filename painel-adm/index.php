<?php
// Inclui o arquivo de conexão com o banco de dados
require_once("../conexao.php");

// Inicia a sessão para poder acessar as variáveis de sessão
@session_start();

// ============================================
// VERIFICAÇÃO DE SEGURANÇA - ACESSO RESTRITO
// ============================================
// Verifica se o usuário NÃO está logado (nível_usuario vazio) OU se não é admin
// Se não atender aos requisitos, redireciona para a página de login
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// ============================================
// RECUPERAR DADOS DO USUÁRIO LOGADO
// ============================================
// Busca no banco os dados do usuário usando o ID armazenado na sessão
$query = $pdo->query("SELECT * FROM usuarios where id = '$_SESSION[id_usuario]'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
// Armazena os dados em variáveis para usar no layout (nome, CPF, email)
$nome_usu = @$res[0]['nome'];
$cpf_usu = @$res[0]['cpf'];
$email_usu = @$res[0]['email'];

// ============================================
// VARIÁVEIS PARA CONTROLE DOS MENUS
// ============================================
// Captura o parâmetro 'pag' da URL para saber qual página incluir
$pag = @$_GET["pag"];

// Nomes dos arquivos que serão incluídos conforme o menu selecionado
$menu1 = "mecanicos";
$menu2 = "recepcionistas";
$menu3 = "fornecedores";
$menu4 = "categorias";
$menu5 = "produtos";
$menu6 = "estoque";
$menu7 = "compras";
$menu8 = "servicos";
$menu9 = "vendas";

// ============================================
// VERIFICAÇÃO DE ESTOQUE BAIXO
// ============================================
// Busca produtos com estoque abaixo do nível mínimo definido ($nivel_estoque)
// $nivel_estoque é uma variável configurada no sistema (ex: 10 unidades)
$query = $pdo->query("SELECT * FROM produtos where estoque < '$nivel_estoque' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nivel_est = @count($res); // Conta quantos produtos estão com estoque baixo

// Define a cor do ícone (amarelo se houver itens, vazio se não)
if($nivel_est > 0){
    $cor_menu = "text-warning"; // Classe do Bootstrap para cor amarela
} else {
    $cor_menu = "";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Configuração de viewport para responsividade em dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Hugo Vasconcelos">

    <title>Painel Administrativo</title>

    <!-- ============================================ -->
    <!-- CSS - FOLHAS DE ESTILO                       -->
    <!-- ============================================ -->
    <!-- Font Awesome 5 - Biblioteca de ícones -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Fonte Nunito do Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Tema SB Admin 2 - Base do layout administrativo -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Estilos personalizados do sistema -->
    <link href="../css/style.css" rel="stylesheet">
    <!-- Estilos específicos do painel admin (sobrescreve o tema quando necessário) -->
    <link href="../css/painel-admin.css" rel="stylesheet">
    
    <!-- CSS do DataTables - para tabelas com busca e paginação -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- ============================================ -->
    <!-- SCRIPTS CARREGADOS NO HEAD (necessários para alguns plugins) -->
    <!-- ============================================ -->
    <!-- jQuery e Bootstrap JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Favicon - ícone da aba do navegador -->
    <link rel="shortcut icon" href="../img/logo-favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>

<body id="page-top">
    <!-- ============================================ -->
    <!-- PAGE WRAPPER - Container principal           -->
    <!-- ============================================ -->
    <div id="wrapper">

        <!-- ============================================ -->
        <!-- SIDEBAR - Menu lateral                       -->
        <!-- ============================================ -->
        <!-- bg-gradient-dark: fundo escuro | sidebar-dark: texto claro -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            
            <!-- Sidebar - Brand (logo/título) -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">
                    <i class="fas fa-cog mr-2"></i> Admin
                </div>
            </a>

            <!-- Divisores visuais -->
            <hr class="sidebar-divider my-0">
            <hr class="sidebar-divider">

            <!-- Título da seção -->
            <div class="sidebar-heading">Cadastros</div>

            <!-- ===== MENU PESSOAS ===== -->
            <li class="nav-item">
                <!-- Link principal que abre o submenu (collapse) -->
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo">
                    <i class="fas fa-users"></i>
                    <span>Pessoas</span>
                </a>
                <!-- Submenu com opções -->
                <div id="collapseTwo" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu1 ?>">Mecanicos</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu2 ?>">Recepcionistas</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu3 ?>">Fornecedores</a>
                    </div>
                </div>
            </li>

            <!-- ===== MENU PRODUTOS ===== -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities">
                    <i class="fas fa-plus"></i>
                    <span>Produtos</span>
                </a>
                <div id="collapseUtilities" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu4 ?>">Categorias</a>
                        <a class="collapse-item" href="index.php?pag=<?php echo $menu5 ?>">Produtos</a>
                    </div>
                </div>
            </li>

            <!-- ===== MENU TIPO SERVIÇO ===== -->
            <!-- Item simples (sem submenu) -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?pag=<?php echo $menu8 ?>">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Tipo Serviço</span>
                </a>
            </li>

            <!-- Divisor -->
            <hr class="sidebar-divider">

            <!-- Seção Consultas -->
            <div class="sidebar-heading">Consultas</div>

            <!-- ===== ESTOQUE BAIXO ===== -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?pag=<?php echo $menu6 ?>">
                    <!-- Ícone muda de cor se houver estoque baixo -->
                    <i class="fas fa-fw fa-chart-area <?php echo $cor_menu ?>"></i>
                    <span>Estoque Baixo</span>
                    <!-- Mostra badge com quantidade se houver itens -->
                    <?php if($nivel_est > 0): ?>
                        <span class="badge badge-warning ml-2"><?php echo $nivel_est ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <!-- ===== COMPRAS ===== -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?pag=<?php echo $menu7 ?>">
                    <i class="fas fa-coins fa-chart-area"></i>
                    <span>Compras</span>
                </a>
            </li>

            <!-- ===== VENDAS ===== -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?pag=<?php echo $menu9 ?>">
                    <i class="fas fa-coins fa-chart-area"></i>
                    <span>Vendas</span>
                </a>
            </li>

            <!-- Divisor -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- ===== RELATÓRIOS ===== -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRel">
                    <i class="fas fa-file"></i>
                    <span>Relatórios</span>
                </a>
                <div id="collapseRel" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!-- Links que abrem modais de relatórios -->
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelServicos">Serviços</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelOrc">Orçamentos</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelMov">Movimentações</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelPagar">Contas à Pagar</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelReceber">Contas à Receber</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelCompras">Compras</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#ModalRelVendas">Vendas</a>
                        <!-- Links que abrem em nova aba (target="_blank") -->
                        <a target="_blank" class="collapse-item" href="../rel/rel_veiculos.php">Veículos Oficina</a>
                        <a target="_blank" class="collapse-item" href="../rel/rel_produtos.php">Catalogo Produtos</a>
                    </div>
                </div>
            </li>

            <!-- ===== LINK PARA PAINEL RECEPÇÃO ===== -->
            <li class="nav-item">
                <a target="_blank" class="nav-link" href="../painel-recepcao">
                    <i class="fas fa-coins fa-chart-area"></i>
                    <span>Painel Recepção</span>
                </a>
            </li>

            <!-- Botão para recolher/expandir sidebar (apenas desktop) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <!-- ============================================ -->
        <!-- CONTENT WRAPPER - Área de conteúdo           -->
        <!-- ============================================ -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                
                <!-- ===== TOPBAR ===== -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Botão para mobile (menu hamburger) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Logo (comentado, desativado) -->
                    <!-- <img src="../img/logo2.png" width="85"> -->

                    <!-- Menu do topo (lado direito) -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Dropdown do usuário -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <i class="fas fa-user-circle mr-1"></i> <?php echo $nome_usu; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="../img/sem-foto.jpg">
                            </a>
                            <!-- Menu dropdown do usuário -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#ModalPerfil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i> Editar Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i> Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- ===== CONTEÚDO DINÂMICO ===== -->
                <div class="container-fluid">
                    <?php 
                    // Inclui o arquivo correspondente à página selecionada no menu
                    if ($pag == null) { 
                        include_once("home.php"); // Página inicial
                    } else if ($pag == $menu1) {
                        include_once($menu1.".php"); // Mecânicos
                    } else if ($pag == $menu2) {
                        include_once($menu2.".php"); // Recepcionistas
                    } else if ($pag == $menu3) {
                        include_once($menu3.".php"); // Fornecedores
                    } else if ($pag == $menu4) {
                        include_once($menu4.".php"); // Categorias
                    } else if ($pag == $menu5) {
                        include_once($menu5.".php"); // Produtos
                    } else if ($pag == $menu6) {
                        include_once($menu6.".php"); // Estoque
                    } else if ($pag == $menu7) {
                        include_once($menu7.".php"); // Compras
                    } else if ($pag == $menu8) {
                        include_once($menu8.".php"); // Serviços
                    } else if ($pag == $menu9) {
                        include_once($menu9.".php"); // Vendas
                    } else {
                        include_once("home.php"); // Padrão: home
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SCROLL TO TOP - Botão para voltar ao topo    -->
    <!-- ============================================ -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- ============================================ -->
    <!-- MODAL EDITAR PERFIL                          -->
    <!-- ============================================ -->
    <div class="modal fade" id="ModalPerfil" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Perfil</h5>
                    <button class="close" type="button" data-dismiss="modal">×</button>
                </div>

                <!-- Formulário de edição (envia via AJAX) -->
                <form id="form-perfil" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input value="<?php echo $nome_usu ?>" type="text" class="form-control" name="nome_usu" placeholder="Nome">
                        </div>
                        <div class="form-group">
                            <label>CPF</label>
                            <input value="<?php echo $cpf_usu ?>" type="text" class="form-control" name="cpf_usu" placeholder="CPF">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input value="<?php echo $email_usu ?>" type="email" class="form-control" name="email_usu" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" class="form-control" name="senha_usu" placeholder="Nova senha">
                        </div>
                        <div id="mensagem" class="mr-4"></div>
                    </div>
                    <div class="modal-footer">
                        <!-- Campos ocultos com dados do usuário -->
                        <input type="hidden" name="id_usu" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="antigo_usu" value="<?php echo $cpf_usu ?>">
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar-perfil" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAIS DE RELATÓRIOS (arquivo externo)       -->
    <!-- ============================================ -->
    <?php require_once("../modal-relatorios.php"); ?>

    <!-- ============================================ -->
    <!-- OVERLAY PARA MOBILE (sidebar flutuante)      -->
    <!-- ============================================ -->
    <div class="sidebar-overlay"></div>

    <!-- ============================================ -->
    <!-- SCRIPTS JAVASCRIPT                            -->
    <!-- ============================================ -->
    <!-- Plugin para easing (animações suaves) -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Script principal do tema SB Admin 2 -->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Plugins para gráficos (Chart.js) -->
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <!-- Plugins para DataTables (tabelas com busca) -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>

    <!-- Scripts para máscaras de campos (CPF, telefone, etc) -->
    <script src="../js/mascaras.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
    
    <!-- ============================================ -->
    <!-- AJAX PARA EDIÇÃO DO PERFIL                   -->
    <!-- ============================================ -->
    <script type="text/javascript">
    $("#form-perfil").submit(function(event) {
        event.preventDefault(); // Impede o envio tradicional do formulário
        
        var formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: "editar-perfil.php", // Arquivo que processa a requisição
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem').removeClass(); // Remove classes anteriores
                
                // Verifica se salvou com sucesso
                if (mensagem.trim() == "Salvo com Sucesso!") {
                    $('#btn-fechar-perfil').click(); // Fecha o modal
                    window.location = "index.php"; // Recarrega a página
                } else {
                    $('#mensagem').addClass('text-danger'); // Mostra erro em vermelho
                }
                $('#mensagem').text(mensagem); // Exibe a mensagem
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
    </script>
</body>
</html>