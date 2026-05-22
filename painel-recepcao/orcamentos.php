<?php
/**
 * ============================================================
 * ORÇAMENTOS — PAINEL RECEPÇÃO
 * Listagem, exclusão e aprovação de orçamentos
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "orcamentos";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

// Captura o parâmetro 'funcao' da URL (se existir)
$funcao = @$_GET['funcao'];

?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE ORÇAMENTOS (DataTables)
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
                        <th>Cliente</th>
                        <th>Veículo</th>
                        <th>Valor</th>
                        <th>Serviço</th>
                        <th>Data</th>
                        <th>Mecânico</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todos os orçamentos
                     * Ordenados por status (abertos primeiro)
                     * e por ID (mais antigos primeiro)
                     */
                    $query = $pdo->query("SELECT * FROM orcamentos order by status asc, id asc ");
                    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada orçamento retornado
                    for ($i = 0; $i < @count($res); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res[$i] as $key => $value) {
                        }

                        // Extrai os dados do orçamento
                        $cliente      = $res[$i]['cliente'];
                        $veiculo      = $res[$i]['veiculo'];
                        $descricao    = $res[$i]['descricao'];
                        $valor        = $res[$i]['valor'];
                        $servico      = $res[$i]['servico'];
                        $data         = $res[$i]['data'];
                        $data_entrega = $res[$i]['data_entrega'];
                        $garantia     = $res[$i]['garantia'];
                        $mecanico     = $res[$i]['mecanico'];
                        $status       = $res[$i]['status'];
                        $id           = $res[$i]['id'];

                        // Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data = implode('/', array_reverse(explode('-', $data)));

                        // Formata valor monetário (ex: 1500.00 → 1.500,00)
                        $valor = number_format($valor, 2, ',', '.');

                        // Busca o nome e email do cliente pelo CPF
                        $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cli  = $res_cat[0]['nome'];
                        $email_cli = $res_cat[0]['email'];

                        // Busca marca e modelo do veículo pelo ID
                        $query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $modelo    = $res_cat[0]['modelo'];
                        $marca     = $res_cat[0]['marca'];

                        // Busca o nome do serviço pelo ID
                        $query_cat = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_serv = $res_cat[0]['nome'];

                        // Busca o nome do mecânico pelo CPF
                        $query_cat     = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
                        $res_cat       = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_mecanico = $res_cat[0]['nome'];

                        /**
                         * Define a cor do status:
                         * Vermelho  = Aberto
                         * Azul      = Aprovado
                         * Verde     = Concluído
                         */
                        if ($status == 'Aberto') {
                            $cor_pago = 'text-danger';
                        } else if ($status == 'Aprovado') {
                            $cor_pago = 'text-primary';
                        } else {
                            $cor_pago = 'text-success';
                        }
                    ?>

                        <!-- Linha da tabela para cada orçamento -->
                        <tr>
                            <!-- Cliente com indicador colorido de status -->
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
                                <?php echo $nome_cli ?>
                            </td>
                            <td><?php echo $marca . ' ' . $modelo ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $nome_serv ?></td>
                            <td><?php echo $data ?></td>
                            <td><?php echo $nome_mecanico ?></td>

                            <!-- Coluna de ações -->
                            <td>
                                <!-- Imprimir orçamento (sempre disponível) -->
                                <a href="../painel-mecanico/rel/rel_orcamento.php?id=<?php echo $id ?>"
                                   target="_blank"
                                   class='text-info mr-1'
                                   title='Imprimir Orçamento'>
                                    <i class='far fa-file-alt'></i>
                                </a>

                                <!-- Ações disponíveis apenas para orçamentos com status "Aberto" -->
                                <?php if ($status == 'Aberto') { ?>

                                    <!-- Excluir orçamento -->
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                       class='text-danger mr-1'
                                       title='Excluir Registro'>
                                        <i class='far fa-trash-alt'></i>
                                    </a>

                                    <!-- Enviar orçamento por email -->
                                    <a href="../painel-mecanico/rel/rel_orcamento.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>"
                                       target="_blank"
                                       class='text-info mr-1'
                                       title='Email Orçamento'>
                                        <i class='far fa-envelope'></i>
                                    </a>

                                <?php } ?>

                                <!-- Aprovar orçamento (sempre disponível) -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>"
                                   class='text-success mr-1'
                                   title='Aprovar Orçamento'>
                                    <i class='fas fa-check'></i>
                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CONFIRMAÇÃO DE EXCLUSÃO DE ORÇAMENTO
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
                <div align="center" id="mensagem_excluir" class=""></div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        id="btn-cancelar-excluir">
                    Cancelar
                </button>

                <form method="post">
                    <!-- ID do orçamento a ser excluído -->
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
     MODAL: CONFIRMAÇÃO DE APROVAÇÃO DE ORÇAMENTO
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
     id="modal-aprovar"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Aprovar Orçamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Deseja realmente Aprovar este Orçamento?</p>

                <!-- Área para mensagem de retorno da aprovação -->
                <div align="center" id="mensagem_orc" class=""></div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        id="btn-cancelar-orc">
                    Cancelar
                </button>

                <form method="post">
                    <!-- ID do orçamento a ser aprovado -->
                    <input type="hidden"
                           id="id"
                           name="id"
                           value="<?php echo @$_GET['id'] ?>"
                           required>

                    <button type="button"
                            id="btn-orc"
                            name="btn-orc"
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
     AJAX: EXCLUSÃO DE ORÇAMENTO
     Envia o ID para orcamentos/excluir.php
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
                    }
                    $('#mensagem_excluir').text(mensagem)
                },
            })
        })
    })
</script>


<!-- ═══════════════════════════════════════════════════════
     AJAX: APROVAÇÃO DE ORÇAMENTO
     Envia o ID para orcamentos/aprovar.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";

        // Clique no botão de aprovar (dentro do modal de confirmação)
        $('#btn-orc').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/aprovar.php",
                method: "post",
                data: $('form').serialize(), // Serializa o ID do formulário
                dataType: "text",

                success: function(mensagem) {
                    if (mensagem.trim() === 'Aprovado com Sucesso!') {
                        // Fecha o modal e recarrega a página
                        $('#btn-cancelar-orc').click();
                        window.location = "index.php?pag=" + pag;
                    }
                    $('#mensagem_orc').text(mensagem)
                },
            })
        })
    })
</script>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {

        // Captura a função vinda da URL (ex: 'editar')
        var funcao = "<?= $funcao ?>";

        /**
         * Se a função for 'editar', dispara o clique no botão de busca
         * Caso contrário, exibe texto de orientação no elemento #div-veiculo
         */
        if (funcao.trim() === 'editar') {
            $('#btn-buscar').click();
        } else {
            $('#div-veiculo').text('Busque pelo CPF ao Lado');
        }

        // Inicializa DataTables na tabela principal (ordenação desabilitada)
        $('#dataTable').dataTable({
            "ordering": false
        });

        // Inicializa DataTables na tabela secundária (ordenação desabilitada)
        $('#dataTable2').dataTable({
            "ordering": false
        });

    });
</script>