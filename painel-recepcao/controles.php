<?php
/**
 * ============================================================
 * CONTROLES (ENTRADA DE VEÍCULOS) — PAINEL RECEPÇÃO
 * Listagem e exclusão de registros de entrada de veículos
 * na oficina para execução de serviços
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "controles";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE ENTRADA DE VEÍCULOS (DataTables)
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
                        <th>Modelo</th>
                        <th>Placa</th>
                        <th>Cliente</th>
                        <th>Mecânico</th>
                        <th>Data Entrada</th>
                        <th>Serviço</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todos os controles de entrada de veículos
                     * Ordenados por ID (mais antigos primeiro)
                     */
                    $query_c = $pdo->query("SELECT * FROM controles order by id asc ");
                    $res_c   = $query_c->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada controle de entrada retornado
                    for ($i = 0; $i < @count($res_c); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res_c[$i] as $key => $value) {
                        }

                        // Extrai os dados do controle
                        $veiculo   = $res_c[$i]['veiculo'];
                        $mecanico  = $res_c[$i]['mecanico'];
                        $data      = $res_c[$i]['data'];
                        $descricao = $res_c[$i]['descricao'];
                        $id        = $res_c[$i]['id'];

                        // Busca dados do veículo pelo ID
                        $query   = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                        $res     = $query->fetchAll(PDO::FETCH_ASSOC);
                        $marca   = $res[0]['marca'];
                        $modelo  = $res[0]['modelo'];
                        $placa   = $res[0]['placa'];
                        $cliente = $res[0]['cliente'];

                        // Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data = implode('/', array_reverse(explode('-', $data)));

                        // Busca o nome do cliente pelo CPF
                        $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cli  = $res_cat[0]['nome'];

                        // Busca o nome do mecânico pelo CPF
                        $query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_mec  = $res_cat[0]['nome'];
                    ?>

                        <!-- Linha da tabela para cada controle de entrada -->
                        <tr>
                            <!-- Marca - Modelo -->
                            <td><?php echo $marca . ' - ' . $modelo ?></td>

                            <!-- Placa do veículo -->
                            <td><?php echo $placa ?></td>

                            <!-- Nome do cliente -->
                            <td><?php echo $nome_cli ?></td>

                            <!-- Nome do mecânico responsável -->
                            <td><?php echo $nome_mec ?></td>

                            <!-- Data de entrada -->
                            <td><?php echo $data ?></td>

                            <!-- Descrição do serviço -->
                            <td><?php echo $descricao ?></td>

                            <!-- Coluna de ações -->
                            <td>
                                <!-- Excluir controle de entrada -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                   class='text-danger mr-1'
                                   title='Excluir Registro'>
                                    <i class='far fa-trash-alt'></i>
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
     MODAL: CONFIRMAÇÃO DE EXCLUSÃO DE CONTROLE
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
                    <!-- ID do controle a ser excluído -->
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


<?php
/**
 * ============================================================
 * LÓGICA DE ABERTURA DO MODAL DE EXCLUSÃO
 * Verifica o parâmetro 'funcao' na URL e exibe o modal
 * ============================================================
 */

// Exclusão → abre modal de confirmação de exclusão
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: EXCLUSÃO DE CONTROLE DE ENTRADA
     Envia o ID para controles/excluir.php
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
     FUNÇÃO: CARREGAR IMAGEM (PRÉ-VISUALIZAÇÃO)
     Exibe preview da imagem selecionada no input file
     (Mantida do original - usada em outros contextos)
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target');
        var file   = document.querySelector("input[type=file]").files[0];
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


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de controles
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables com ordenação desabilitada
        $('#dataTable').dataTable({
            "ordering": false
        })
    });
</script>