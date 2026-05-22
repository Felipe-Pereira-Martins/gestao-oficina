<?php
/* ============================================================
   PAINEL RECEPÇÃO — index.php
   Lógica PHP original mantida integralmente.
   Apenas o HTML/estrutura visual foi refatorado.
   ============================================================ */
require_once("../conexao.php");
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// RECUPERAR DADOS DO USUÁRIO (sem alteração)
$query = $pdo->query("SELECT * FROM usuarios where id = '$_SESSION[id_usuario]'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_usu  = @$res[0]['nome'];
$cpf_usu   = @$res[0]['cpf'];
$email_usu = @$res[0]['email'];

// Inicial do nome para o avatar
$inicial = strtoupper(mb_substr($nome_usu, 0, 1));

// Variáveis para o menu (sem alteração)
$pag   = @$_GET["pag"];
$menu1  = "pagar";
$menu2  = "receber";
$menu3  = "veiculos";
$menu4  = "clientes";
$menu5  = "produtos";
$menu6  = "movimentacoes";
$menu7  = "compras";
$menu8  = "orcamentos";
$menu9  = "servicos";
$menu10 = "controles";
$menu11 = "retornos";

// Helper: verifica se a página atual corresponde ao menu
function isActive($pag, $menus) {
    return in_array($pag, (array)$menus) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Painel de Recepção — Gestão de Oficina">
    <meta name="author" content="Martins Sistemas">
    <title>Painel Recepção — Gestão de Oficina</title>

    <!-- Fontes: mantido Nunito (já existente no sistema) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome (original) -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap/SB Admin 2 (originais — mantidos) -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- CSS premium novo — carregado por último para sobrescrever -->
    <link href="../css/painel-recepcao.css" rel="stylesheet">
    <!-- Mantém o style.css original caso ele tenha overrides específicos de páginas filhas -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- jQuery (carregado antes do </head> para evitar erros em páginas incluídas) -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon (original) -->
    <link rel="shortcut icon" href="../img/logo-favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>

<body id="page-top">

<!-- ============================================================
     WRAPPER PRINCIPAL
     ============================================================ -->
<div id="wrapper">

    <!-- ====================================================
         SIDEBAR
         ==================================================== -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- ── Brand / Logo ── -->
        <a class="sidebar-brand d-flex align-items-center" href="index.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-cog"></i>
            </div>
            <div class="sidebar-brand-text mx-3">
                Recepção
                <small>Gestão de Oficina</small>
            </div>
        </a>

        <!-- ── Divider ── -->
        <hr class="sidebar-divider my-0">

        <!-- ────────────────────────────────────────────────
             SEÇÃO: CONTAS
             ──────────────────────────────────────────────── -->
        <div class="sidebar-heading">Financeiro</div>

        <li class="nav-item <?= isActive($pag, [$menu1, $menu2]) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContas"
               aria-expanded="false" aria-controls="collapseContas">
                <i class="fas fa-coins"></i>
                <span>Pagar e Receber</span>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <div id="collapseContas" class="collapse <?= ($pag == $menu1 || $pag == $menu2) ? 'show' : '' ?>"
                 data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <a class="collapse-item <?= isActive($pag, $menu1) ?>"
                       href="index.php?pag=<?= $menu1 ?>">Contas à Pagar</a>
                    <a class="collapse-item <?= isActive($pag, $menu2) ?>"
                       href="index.php?pag=<?= $menu2 ?>">Contas à Receber</a>
                </div>
            </div>
        </li>

        <!-- ────────────────────────────────────────────────
             SEÇÃO: CADASTROS
             ──────────────────────────────────────────────── -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Cadastros</div>

        <li class="nav-item <?= isActive($pag, [$menu3, $menu4]) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCadastros"
               aria-expanded="false" aria-controls="collapseCadastros">
                <i class="fas fa-plus-circle"></i>
                <span>Cadastros</span>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <div id="collapseCadastros" class="collapse <?= ($pag == $menu3 || $pag == $menu4) ? 'show' : '' ?>"
                 data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <a class="collapse-item <?= isActive($pag, $menu4) ?>"
                       href="index.php?pag=<?= $menu4 ?>">Clientes</a>
                    <a class="collapse-item <?= isActive($pag, $menu3) ?>"
                       href="index.php?pag=<?= $menu3 ?>">Veículos</a>
                </div>
            </div>
        </li>

        <!-- ────────────────────────────────────────────────
             SEÇÃO: CONSULTAS
             ──────────────────────────────────────────────── -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Consultas</div>

        <li class="nav-item <?= isActive($pag, [$menu6,$menu7,$menu8,$menu9,$menu10,$menu11]) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseConsultas"
               aria-expanded="false" aria-controls="collapseConsultas">
                <i class="fas fa-search"></i>
                <span>Consultas</span>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <div id="collapseConsultas" class="collapse
                <?= in_array($pag, [$menu6,$menu7,$menu8,$menu9,$menu10,$menu11]) ? 'show' : '' ?>"
                 data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <a class="collapse-item <?= isActive($pag, $menu8) ?>"
                       href="index.php?pag=<?= $menu8 ?>">Orçamentos</a>
                    <a class="collapse-item <?= isActive($pag, $menu9) ?>"
                       href="index.php?pag=<?= $menu9 ?>">Serviços</a>
                    <a class="collapse-item <?= isActive($pag, $menu6) ?>"
                       href="index.php?pag=<?= $menu6 ?>">Movimentações</a>
                    <a class="collapse-item <?= isActive($pag, $menu7) ?>"
                       href="index.php?pag=<?= $menu7 ?>">Compras</a>
                    <a class="collapse-item <?= isActive($pag, $menu10) ?>"
                       href="index.php?pag=<?= $menu10 ?>">Entrada Veículos</a>
                    <a class="collapse-item <?= isActive($pag, $menu11) ?>"
                       href="index.php?pag=<?= $menu11 ?>">Serviços Retorno</a>
                </div>
            </div>
        </li>

        <!-- ────────────────────────────────────────────────
             SEÇÃO: RELATÓRIOS
             ──────────────────────────────────────────────── -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRel"
               aria-expanded="false" aria-controls="collapseRel">
                <i class="fas fa-file-alt"></i>
                <span>Relatórios</span>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <div id="collapseRel" class="collapse" data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <!-- data-toggle/data-target originais mantidos -->
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelServicos">Serviços</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelOrc">Orçamentos</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelMov">Movimentações</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelPagar">Contas à Pagar</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelReceber">Contas à Receber</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelCompras">Compras</a>
                    <a class="collapse-item" href="" data-toggle="modal" data-target="#ModalRelVendas">Vendas</a>
                    <a target="_blank" class="collapse-item" href="../rel/rel_veiculos.php">Veículos Oficina</a>
                    <a target="_blank" class="collapse-item" href="../rel/rel_produtos.php">Catálogo Produtos</a>
                </div>
            </div>
        </li>

        <!-- ── Divider e toggle ── -->
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="border-0" id="sidebarToggle" title="Minimizar sidebar"></button>
        </div>

    </ul>
    <!-- /Sidebar -->

    <!-- ====================================================
         CONTENT WRAPPER
         ==================================================== -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- ── TOPBAR ── -->
            <nav class="navbar navbar-expand topbar static-top">

                <!-- Botão toggle mobile (original) -->
                <button id="sidebarToggleTop" class="btn d-md-none mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Espaço flexível -->
                <div class="d-none d-md-flex align-items-center">
                    <!-- Breadcrumb leve opcional -->
                    <span style="font-size:12px;color:var(--text-muted);">
                        <i class="fas fa-home" style="font-size:11px;"></i>
                        &nbsp;Painel de Recepção
                    </span>
                </div>

                <!-- Itens da direita -->
                <ul class="navbar-nav ml-auto align-items-center">

                    <!-- Divider visual -->
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Dropdown do usuário (lógica original mantida) -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <!-- Avatar com inicial do nome -->
                            <div class="user-avatar"><?= $inicial ?: 'U' ?></div>

                            <div class="d-none d-md-block ml-2" style="line-height:1.3;">
                                <div class="topbar-user-name"><?= htmlspecialchars($nome_usu) ?></div>
                                <div class="topbar-user-role">Recepção</div>
                            </div>

                            <i class="fas fa-chevron-down ml-2" style="font-size:10px;opacity:0.5;"></i>
                        </a>

                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu dropdown-menu-right shadow"
                             aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <?= htmlspecialchars($nome_usu) ?>
                            </div>
                            <!-- Editar perfil (original: abre modal) -->
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#ModalPerfil">
                                <i class="fas fa-user-edit fa-sm"></i>
                                Editar Perfil
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- Logout (original) -->
                            <a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt fa-sm"></i>
                                Sair do Sistema
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>
            <!-- /Topbar -->

            <!-- ── CONTEÚDO PRINCIPAL ── -->
            <div class="container-fluid">

                <?php
                /* ============================================================
                   ROTEAMENTO PHP — sem alteração alguma na lógica original
                   ============================================================ */
                if (@$pag == null) {
                    @include_once("home.php");
                } else if (@$pag == $menu1) {
                    @include_once(@$menu1 . ".php");
                } else if (@$pag == $menu2) {
                    @include_once(@$menu2 . ".php");
                } else if (@$pag == $menu3) {
                    include_once(@$menu3 . ".php");
                } else if (@$pag == $menu4) {
                    @include_once(@$menu4 . ".php");
                } else if (@$pag == $menu5) {
                    @include_once(@$menu5 . ".php");
                } else if (@$pag == $menu6) {
                    @include_once(@$menu6 . ".php");
                } else if (@$pag == $menu7) {
                    @include_once(@$menu7 . ".php");
                } else if (@$pag == $menu8) {
                    @include_once(@$menu8 . ".php");
                } else if (@$pag == $menu9) {
                    @include_once(@$menu9 . ".php");
                } else if (@$pag == $menu10) {
                    @include_once(@$menu10 . ".php");
                } else if (@$pag == $menu11) {
                    @include_once(@$menu11 . ".php");
                } else {
                    @include_once("home.php");
                }
                ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /content -->
    </div>
    <!-- /content-wrapper -->

</div>
<!-- /wrapper -->

<!-- ============================================================
     SCROLL TO TOP
     ============================================================ -->
<a class="scroll-to-top" id="scrollToTop" href="#page-top" title="Voltar ao topo">
    <i class="fas fa-angle-up"></i>
</a>

<!-- ============================================================
     MODAL — EDITAR PERFIL (lógica e campos originais mantidos)
     ============================================================ -->
<div class="modal fade" id="ModalPerfil" tabindex="-1" role="dialog"
     aria-labelledby="modalPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalPerfilLabel">
                    <i class="fas fa-user-edit mr-2" style="color:var(--red-bright);"></i>
                    Editar Perfil
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-perfil" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="nome_usu">Nome</label>
                        <input value="<?= htmlspecialchars($nome_usu) ?>"
                               type="text" class="form-control"
                               id="nome_usu" name="nome_usu" placeholder="Nome completo">
                    </div>

                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input value="<?= htmlspecialchars($cpf_usu) ?>"
                               type="text" class="form-control"
                               id="cpf" name="cpf_usu" placeholder="000.000.000-00">
                    </div>

                    <div class="form-group">
                        <label for="email_usu">E-mail</label>
                        <input value="<?= htmlspecialchars($email_usu) ?>"
                               type="email" class="form-control"
                               id="email_usu" name="email_usu" placeholder="seu@email.com">
                    </div>

                    <div class="form-group">
                        <label for="senha_usu">Nova Senha</label>
                        <input value="" type="password" class="form-control"
                               id="senha_usu" name="senha_usu"
                               placeholder="Deixe em branco para manter a atual">
                    </div>

                    <!-- Área de mensagem de retorno (original) -->
                    <small>
                        <div id="mensagem" class="mt-2"></div>
                    </small>

                </div>

                <div class="modal-footer">
                    <!-- Campos hidden originais mantidos -->
                    <input value="<?= $_SESSION['id_usuario'] ?>" type="hidden" name="id_usu" id="id_usu">
                    <input value="<?= htmlspecialchars($cpf_usu) ?>" type="hidden" name="antigo_usu" id="antigo_usu">

                    <button type="button" id="btn-fechar-perfil"
                            class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="btn-salvar-perfil" id="btn-salvar-perfil"
                            class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Salvar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ============================================================
     INCLUDES PHP ORIGINAIS (sem alteração)
     ============================================================ -->
<?php require_once("../modal-relatorios.php"); ?>

<!-- ============================================================
     SCRIPTS — mesma ordem do original
     ============================================================ -->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../js/sb-admin-2.min.js"></script>
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="../js/demo/chart-area-demo.js"></script>
<script src="../js/demo/chart-pie-demo.js"></script>
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../js/demo/datatables-demo.js"></script>
<script src="../js/mascaras.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<!-- ============================================================
     AJAX PERFIL — original mantido integralmente
     ============================================================ -->
<script type="text/javascript">
    $("#form-perfil").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "editar-perfil.php",
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem').removeClass();
                if (mensagem.trim() == "Salvo com Sucesso!") {
                    $('#btn-fechar-perfil').click();
                    window.location = "index.php";
                } else {
                    $('#mensagem').addClass('text-danger');
                }
                $('#mensagem').text(mensagem);
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function () {}, false);
                }
                return myXhr;
            }
        });
    });
</script>

<!-- ============================================================
     MELHORIAS DE UX — scroll to top visível / active state dinâmico
     ============================================================ -->
<script>
    // Scroll to top visível após 200px
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 200) {
            $('#scrollToTop').addClass('visible');
        } else {
            $('#scrollToTop').removeClass('visible');
        }
    });

    // Marca collapse-item como active com base na URL atual
    (function () {
        var url = window.location.href;
        $('.collapse-item').each(function () {
            if (this.href && url.indexOf(this.href) !== -1) {
                $(this).addClass('active');
            }
        });
    })();
</script>

</body>
</html>
