<?php
/**
 * ============================================================
 * CONTAS A RECEBER — PAINEL RECEPÇÃO
 * Listagem, lançamento de adiantamento, exclusão e aprovação
 * de contas a receber de clientes
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "receber";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

// Data de vencimento padrão para o formulário (hoje)
$data_venc2 = date('Y-m-d');

?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE CONTAS A RECEBER (DataTables)
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
                        <th>Adiantamento</th>
                        <th>Mecânico</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todas as contas a receber
                     * Ordenadas por status de pagamento (não pagas primeiro)
                     * e por data (mais antigas primeiro)
                     */
                    $query = $pdo->query("SELECT * FROM contas_receber order by pago asc, data asc");
                    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada conta a receber retornada
                    for ($i = 0; $i < @count($res); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res[$i] as $key => $value) {
                        }

                        // Extrai os dados da conta
                        $descricao    = $res[$i]['descricao'];
                        $valor        = $res[$i]['valor'];
                        $adiantamento = $res[$i]['adiantamento'];
                        $mecanico     = $res[$i]['mecanico'];
                        $cliente      = $res[$i]['cliente'];
                        $pago         = $res[$i]['pago'];
                        $data         = $res[$i]['data'];
                        $id           = $res[$i]['id'];

                        // Busca o nome do cliente pelo CPF
                        $query_usu = $pdo->query("SELECT * FROM clientes where cpf = '$cliente'");
                        $res_usu   = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cli  = $res_usu[0]['nome'];

                        // Busca o nome do mecânico pelo CPF
                        $query_usu = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico'");
                        $res_usu   = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                        $nome_mec  = $res_usu[0]['nome'];

                        // Formata valores monetários (ex: 1500.00 → 1.500,00)
                        $valor        = number_format($valor, 2, ',', '.');
                        $adiantamento = number_format($adiantamento, 2, ',', '.');

                        // Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data = implode('/', array_reverse(explode('-', $data)));

                        // Define a cor do status:
                        // Verde = Pago | Vermelho = Pendente
                        if ($pago == 'Sim') {
                            $cor_pago = 'text-success';
                        } else {
                            $cor_pago = 'text-danger';
                        }
                    ?>

                        <!-- Linha da tabela para cada conta a receber -->
                        <tr>
                            <!-- Descrição com indicador colorido de status -->
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
                                <?php echo $descricao ?>
                            </td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $adiantamento ?></td>
                            <td><?php echo $nome_mec ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $data ?></td>

                            <!-- Coluna de ações (disponíveis apenas para contas NÃO pagas) -->
                            <td>
                                <?php if ($pago != 'Sim') { ?>
                                    <!-- Lançar adiantamento -->
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                       class='text-primary mr-1'
                                       title='Lançar Adiantamento'>
                                        <i class='far fa-edit'></i>
                                    </a>

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
     MODAL: LANÇAR ADIANTAMENTO
     Permite registrar um valor de adiantamento na conta
     ═══════════════════════════════════════════════════════ -->
<div class="modal fade"
     id="modalDados"
     tabindex="-1"
     role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Cabeçalho do modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lançar Valor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Formulário de lançamento de valor -->
            <form id="form" method="POST">
                <div class="modal-body">

                    <!-- Campo: Valor do adiantamento -->
                    <div class="form-group">
                        <label>Valor</label>
                        <input type="text"
                               class="form-control"
                               id="valor"
                               name="valor"
                               placeholder="Valor">
                    </div>

                    <!-- Área de mensagem de retorno (validação) -->
                    <small>
                        <div id="mensagem"></div>
                    </small>

                </div>

                <!-- Rodapé do modal -->
                <div class="modal-footer">
                    <!-- Campo oculto com ID da conta -->
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


<?php
/**
 * ============================================================
 * LÓGICA DE ABERTURA DOS MODAIS
 * Verifica o parâmetro 'funcao' na URL e exibe o modal correto
 * ============================================================
 */

// Lançar adiantamento → abre modal de lançamento de valor
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

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: EXCLUSÃO DE CONTA A RECEBER
     Envia o ID para receber/excluir.php
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
     Envia o ID para receber/aprovar.php
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
     AJAX: LANÇAMENTO DE ADIANTAMENTO
     Envia o valor para receber/adiantamento.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";

        // Clique no botão de salvar (dentro do modal de lançamento)
        $('#btn-salvar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/adiantamento.php",
                method: "post",
                data: $('form').serialize(), // Serializa o valor e ID do formulário
                dataType: "text",

                success: function(mensagem) {
                    if (mensagem.trim() === 'Salvo com Sucesso!') {
                        // Fecha o modal e recarrega a página
                        $('#btn-fechar').click();
                        window.location = "index.php?pag=" + pag;
                    } else {
                        // Exibe mensagem de erro em vermelho
                        $('#mensagem').addClass('text-danger')
                    }
                    $('#mensagem').text(mensagem)
                },
            })
        })
    })
</script>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de contas a receber
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