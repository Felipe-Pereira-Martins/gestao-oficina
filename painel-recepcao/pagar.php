<?php
/**
 * ============================================================
 * CONTAS A PAGAR — PAINEL RECEPÇÃO
 * Listagem, cadastro, edição, exclusão e aprovação
 * de contas a pagar da oficina
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "pagar";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

// Data de vencimento padrão para o formulário (hoje)
$data_venc2 = date('Y-m-d');

?>

<!-- ── BOTÕES DE AÇÃO (TOPO) ── -->
<div class="row mt-4 mb-4">
    <!-- Botão desktop: texto completo -->
    <a type="button"
       class="btn-secondary btn-sm ml-3 d-none d-md-block"
       href="index.php?pag=<?php echo $pag ?>&funcao=novo">
        Nova Conta
    </a>
    <!-- Botão mobile: apenas ícone "+" -->
    <a type="button"
       class="btn-primary btn-sm ml-3 d-block d-sm-none"
       href="index.php?pag=<?php echo $pag ?>&funcao=novo">
        +
    </a>
</div>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE CONTAS A PAGAR (DataTables)
     ═══════════════════════════════════════════════════════ -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered"
                   id="dataTable"
                   width="100%"
                   cellspacing="0">

                <!-- Cabeçalho da tabela -->
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Funcionário</th>
                        <th>Data Vencimento</th>
                        <th>Arquivo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todas as contas a pagar
                     * Ordenadas por status de pagamento (não pagas primeiro)
                     * e por data de vencimento (mais próximas primeiro)
                     */
                    $query = $pdo->query("SELECT * FROM contas_pagar order by pago asc, data_venc asc");
                    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada conta a pagar retornada
                    for ($i = 0; $i < @count($res); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res[$i] as $key => $value) {
                        }

                        // Extrai os dados da conta
                        $descricao   = $res[$i]['descricao'];
                        $valor       = $res[$i]['valor'];
                        $funcionario = $res[$i]['funcionario'];
                        $data_venc   = $res[$i]['data_venc'];
                        $pago        = $res[$i]['pago'];
                        $imagem      = $res[$i]['imagem'];
                        $id          = $res[$i]['id'];

                        // Busca o nome do funcionário pelo CPF
                        $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario'");
                        $res_usu   = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                        $nome_func = $res_usu[0]['nome'];

                        // Formata valor monetário (ex: 1500.00 → 1.500,00)
                        $valor = number_format($valor, 2, ',', '.');

                        // Converte data de vencimento de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data_venc = implode('/', array_reverse(explode('-', $data_venc)));

                        // Define a cor do status:
                        // Verde = Pago | Vermelho = Pendente
                        if ($pago == 'Sim') {
                            $cor_pago = 'text-success';
                        } else {
                            $cor_pago = 'text-danger';
                        }
                    ?>

                        <!-- Linha da tabela para cada conta a pagar -->
                        <tr>
                            <!-- Descrição com indicador colorido de status -->
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>

                                <?php if ($descricao != 'Compra de Produtos') {
                                    // Conta comum: exibe apenas a descrição
                                    echo $descricao;
                                } else {
                                    // Compra de produtos: exibe link para ver detalhes da compra
                                    echo '<a class="text-dark" href="index.php?pag=' . $pag . '&funcao=compra&id=' . $id . '" title="Ver Detalhes Compra">' . $descricao . '</a>';
                                } ?>
                            </td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $nome_func ?></td>
                            <td><?php echo $data_venc ?></td>

                            <!-- Coluna de arquivo anexo -->
                            <td>
                                <?php
                                // Exibe link para o arquivo apenas se houver imagem válida
                                if ($imagem != "" and $imagem != "sem-foto.jpg") {
                                    echo '<a href="../img/contas/' . $imagem . '" title="Clique para ver o arquivo" target="_blank">Ver Arquivo</a>';
                                }
                                ?>
                            </td>

                            <!-- Coluna de ações (disponíveis apenas para contas NÃO pagas) -->
                            <td>
                                <?php if ($pago != 'Sim') { ?>

                                    <?php if ($descricao != 'Compra de Produtos') { ?>
                                        <!-- Editar conta (não disponível para compras de produtos) -->
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                           class='text-primary mr-1'
                                           title='Editar Dados'>
                                            <i class='far fa-edit'></i>
                                        </a>
                                    <?php } ?>

                                    <!-- Excluir conta -->
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                       class='text-danger mr-1'
                                       title='Excluir Registro'>
                                        <i class='far fa-trash-alt'></i>
                                    </a>

                                    <!-- Aprovar pagamento -->
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>"
                                       class='text-success mr-1'
                                       title='Aprovar Conta'>
                                        <i class='fas fa-check-square'></i>
                                    </a>

                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CADASTRO / EDIÇÃO DE CONTA A PAGAR
     Modal grande (modal-lg) com upload de imagem
     ═══════════════════════════════════════════════════════ -->
<div class="modal fade"
     id="modalDados"
     tabindex="-1"
     role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- Cabeçalho do modal -->
            <div class="modal-header">
                <?php
                // Se for edição, busca os dados da conta para preencher o formulário
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Registro";
                    $id2    = $_GET['id'];

                    // Busca dados da conta pelo ID
                    $query       = $pdo->query("SELECT * FROM contas_pagar where id = '$id2' ");
                    $res         = $query->fetchAll(PDO::FETCH_ASSOC);
                    $descricao2  = $res[0]['descricao'];
                    $valor2      = $res[0]['valor'];
                    $data_venc2  = $res[0]['data_venc'];
                    $imagem2     = $res[0]['imagem'];
                } else {
                    // Se for novo cadastro
                    $titulo = "Inserir Registro";
                }
                ?>

                <h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Formulário de cadastro/edição -->
            <form id="form" method="POST">
                <div class="modal-body">

                    <div class="row">
                        <!-- Coluna esquerda: campos de texto -->
                        <div class="col-md-6">
                            <!-- Descrição da conta -->
                            <div class="form-group">
                                <label>Descricao</label>
                                <input value="<?php echo @$descricao2 ?>"
                                       type="text"
                                       class="form-control"
                                       id="descricao"
                                       name="descricao"
                                       placeholder="Descrição">
                            </div>

                            <!-- Valor da conta -->
                            <div class="form-group">
                                <label>Valor</label>
                                <input value="<?php echo @$valor2 ?>"
                                       type="text"
                                       class="form-control"
                                       id="valor"
                                       name="valor"
                                       placeholder="Valor">
                            </div>

                            <!-- Data de vencimento -->
                            <div class="form-group">
                                <label>Data Vencimento</label>
                                <input value="<?php echo @$data_venc2 ?>"
                                       type="date"
                                       class="form-control"
                                       id="data_venc"
                                       name="data_venc">
                            </div>
                        </div>

                        <!-- Coluna direita: upload de imagem -->
                        <div class="col-md-6">
                            <!-- Campo de upload -->
                            <div class="form-group">
                                <label>Imagem</label>
                                <input type="file"
                                       value="<?php echo @$imagem2 ?>"
                                       class="form-control-file"
                                       id="imagem"
                                       name="imagem"
                                       onChange="carregarImg();">
                            </div>

                            <!-- Pré-visualização da imagem -->
                            <div id="divImgConta">
                                <?php if (@$imagem2 != "") { ?>
                                    <!-- Se já tiver imagem, exibe a imagem existente -->
                                    <img src="../img/contas/<?php echo $imagem2 ?>"
                                         width="170"
                                         height="170"
                                         id="target">
                                <?php } else { ?>
                                    <!-- Senão, exibe imagem padrão -->
                                    <img src="../img/contas/sem-foto.jpg"
                                         width="170"
                                         height="170"
                                         id="target">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Área de mensagem de retorno (validação) -->
                    <small>
                        <div id="mensagem"></div>
                    </small>

                </div>

                <!-- Rodapé do modal -->
                <div class="modal-footer">
                    <!-- Campo oculto com ID da conta (para edição) -->
                    <input value="<?php echo @$_GET['id'] ?>"
                           type="hidden"
                           name="txtid2"
                           id="txtid2">

                    <button type="button"
                            id="btn-fechar"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                            name="btn-salvar"
                            id="btn-salvar"
                            class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CONFIRMAÇÃO DE EXCLUSÃO
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
     id="modal-deletar"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Excluir Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Deseja realmente Excluir este Registro?</p>

                <!-- Área para mensagem de retorno da exclusão -->
                <small>
                    <div align="center" id="mensagem_excluir" class=""></div>
                </small>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        id="btn-cancelar-excluir">
                    Cancelar
                </button>

                <form method="post">
                    <!-- ID da conta a ser excluída -->
                    <input type="hidden"
                           id="id"
                           name="id"
                           value="<?php echo @$_GET['id'] ?>"
                           required>

                    <button type="button"
                            id="btn-deletar"
                            name="btn-deletar"
                            class="btn btn-danger">
                        Excluir
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CONFIRMAÇÃO DE APROVAÇÃO DE PAGAMENTO
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
     id="modal-aprovar"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Aprovar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Deseja realmente Aprovar este Pagamento?</p>

                <!-- Área para mensagem de retorno da aprovação -->
                <small>
                    <div align="center" id="mensagem_aprovar" class=""></div>
                </small>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        id="btn-cancelar-aprovar">
                    Cancelar
                </button>

                <form method="post">
                    <!-- ID da conta a ser aprovada -->
                    <input type="hidden"
                           id="id"
                           name="id"
                           value="<?php echo @$_GET['id'] ?>"
                           required>

                    <button type="button"
                            id="btn-aprovar"
                            name="btn-deletar"
                            class="btn btn-success">
                        Aprovar
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: DADOS DA COMPRA
     Exibe detalhes de uma compra de produtos vinculada à conta
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
     id="modal-compra"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Dados da Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php
                /**
                 * Busca os detalhes da compra vinculada à conta
                 * Exibe produto, valor, data e funcionário responsável
                 */
                if (@$_GET['funcao'] == 'compra') {

                    $id2 = $_GET['id'];

                    // Busca dados da compra pelo ID da conta
                    $query        = $pdo->query("SELECT * FROM compras where id_conta = '$id2' ");
                    $res          = $query->fetchAll(PDO::FETCH_ASSOC);
                    $produto      = $res[0]['produto'];
                    $valor        = $res[0]['valor'];
                    $funcionario  = $res[0]['funcionario'];
                    $data         = $res[0]['data'];

                    // Formata valor monetário
                    $valor = number_format($valor, 2, ',', '.');

                    // Converte data para formato brasileiro
                    $data = implode('/', array_reverse(explode('-', $data)));

                    // Busca nome e imagem do produto
                    $query_prod    = $pdo->query("SELECT * FROM produtos where id = '$produto' ");
                    $res_prod      = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                    $nome_produto  = $res_prod[0]['nome'];
                    $img_produto   = $res_prod[0]['imagem'];

                    // Busca nome do funcionário
                    $query_prod        = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario' ");
                    $res_prod          = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                    $nome_funcionario  = $res_prod[0]['nome'];
                }
                ?>

                <!-- Layout: imagem à esquerda, dados à direita -->
                <div class="row">
                    <!-- Imagem do produto -->
                    <div class="col-md-3">
                        <img src="../img/produtos/<?php echo $img_produto ?>" width="100%">
                    </div>

                    <!-- Dados da compra -->
                    <div class="col-md-9">
                        <span><b>Nome do Produto: </b> <i><?php echo $nome_produto ?></i></span><br>
                        <span><b>Valor da Compra: </b> <i><?php echo $valor ?></i></span><br>
                        <span><b>Data: </b> <i><?php echo $data ?></i></span><br>
                        <span><b>Funcionário: </b> <i><?php echo $nome_funcionario ?></i></span><br>
                        <br>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<?php
/**
 * ============================================================
 * LÓGICA DE ABERTURA DOS MODAIS
 * Verifica o parâmetro 'funcao' na URL e exibe o modal correto
 * ============================================================
 */

// Novo cadastro → abre modal de cadastro
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

// Edição → abre modal de edição (mesmo modal, mas com dados preenchidos)
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

// Exclusão → abre modal de confirmação de exclusão
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}

// Aprovação → abre modal de confirmação de aprovação
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "aprovar") {
    echo "<script>$('#modal-aprovar').modal('show');</script>";
}

// Detalhes da compra → abre modal com dados da compra
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "compra") {
    echo "<script>$('#modal-compra').modal('show');</script>";
}

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: INSERÇÃO E EDIÇÃO DE CONTAS A PAGAR
     Envia os dados do formulário para pagar/inserir.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    // Intercepta o envio do formulário
    $("#form").submit(function() {
        var pag = "<?= $pag ?>";      // Nome da página atual
        event.preventDefault();        // Impede o envio tradicional
        var formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: pag + "/inserir.php",  // Arquivo que processa a inserção/edição
            type: 'POST',
            data: formData,

            // Callback de sucesso
            success: function(mensagem) {
                $('#mensagem').removeClass()
                if (mensagem.trim() == "Salvo com Sucesso!") {
                    // Fecha o modal e recarrega a página
                    $('#btn-fechar').click();
                    window.location = "index.php?pag=" + pag;
                } else {
                    // Exibe mensagem de erro em vermelho
                    $('#mensagem').addClass('text-danger')
                }
                $('#mensagem').text(mensagem)
            },

            cache: false,        // Não armazena em cache
            contentType: false,  // Deixa o jQuery definir o contentType
            processData: false,  // Não processa os dados (FormData já faz isso)
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // Monitora progresso do upload (se houver arquivo)
                    myXhr.upload.addEventListener('progress', function() {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>


<!-- ═══════════════════════════════════════════════════════
     AJAX: EXCLUSÃO DE CONTA A PAGAR
     Envia o ID para pagar/excluir.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";

        // Clique no botão de excluir (dentro do modal de confirmação)
        $('#btn-deletar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/excluir.php",
                method: "post",
                data: $('form').serialize(), // Serializa o ID do formulário
                dataType: "text",

                success: function(mensagem) {
                    if (mensagem.trim() === 'Excluído com Sucesso!') {
                        // Fecha o modal e recarrega a página
                        $('#btn-cancelar-excluir').click();
                        window.location = "index.php?pag=" + pag;
                    } else {
                        // Exibe mensagem de erro em vermelho
                        $('#mensagem_excluir').addClass('text-danger')
                    }
                    $('#mensagem_excluir').text(mensagem)
                },
            })
        })
    })
</script>


<!-- ═══════════════════════════════════════════════════════
     AJAX: APROVAÇÃO DE PAGAMENTO
     Envia o ID para pagar/aprovar.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";

        // Clique no botão de aprovar (dentro do modal de confirmação)
        $('#btn-aprovar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/aprovar.php",
                method: "post",
                data: $('form').serialize(), // Serializa o ID do formulário
                dataType: "text",

                success: function(mensagem) {
                    if (mensagem.trim() === 'Aprovado com Sucesso!') {
                        // Fecha o modal e recarrega a página
                        $('#btn-cancelar-aprovar').click();
                        window.location = "index.php?pag=" + pag;
                    } else {
                        // Exibe mensagem de erro em vermelho
                        $('#mensagem_aprovar').addClass('text-danger')
                    }
                    $('#mensagem_aprovar').text(mensagem)
                },
            })
        })
    })
</script>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de contas a pagar
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables com ordenação desabilitada
        $('#dataTable').dataTable({
            "ordering": false
        })
    });
</script>


<!-- ═══════════════════════════════════════════════════════
     FUNÇÃO: CARREGAR IMAGEM (PRÉ-VISUALIZAÇÃO)
     Exibe preview da imagem selecionada no input file
     Se for PDF, exibe ícone genérico de PDF
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    function carregarImg() {

        var target  = document.getElementById('target');
        var file    = document.querySelector("input[type=file]").files[0];

        // Extrai o nome e extensão do arquivo
        var arquivo  = file['name'];
        resultado     = arquivo.split(".", 2);

        /**
         * Se for PDF, exibe o ícone de PDF
         * em vez de tentar carregar como imagem
         */
        if (resultado[1] === 'pdf') {
            $('#target').attr('src', "../img/contas/pdf.png");
            return;
        }

        var reader = new FileReader();

        // Quando a leitura terminar, exibe a imagem no elemento target
        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);  // Converte para base64
        } else {
            target.src = "";  // Limpa se nenhum arquivo selecionado
        }
    }
</script>