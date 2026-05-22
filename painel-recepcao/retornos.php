<?php
/**
 * ============================================================
 * RETORNOS (SERVIÇOS DE RETORNO) — PAINEL RECEPÇÃO
 * Listagem de clientes que não retornam há X dias para
 * ações de fidelização (contato/email promocional)
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links
$pag = "retornos";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

/**
 * Calcula as datas para o filtro de retorno
 * $data_hoje     → data atual (ex: 2026-05-13)
 * $data_retorno  → data limite (hoje - dias configurados em $dias_alerta_retorno)
 *                  Clientes com último serviço antes desta data aparecem na lista
 */
$data_hoje    = date('Y-m-d');
$data_retorno = date('Y-m-d', strtotime("-$dias_alerta_retorno days", strtotime($data_hoje)));

?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE RETORNOS (DataTables)
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
                        <th>Telefone</th>
                        <th>Último Serviço</th>
                        <th>Serviço</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca veículos que não retornam há mais de X dias
                     * Filtra por data do serviço e data do último contato
                     * ambos menores ou iguais à data de retorno calculada
                     */
                    $query_c = $pdo->query("SELECT * FROM retornos where data_serv <= '$data_retorno' and data_contato <= '$data_retorno' order by id asc ");
                    $res_c   = $query_c->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada registro de retorno
                    for ($i = 0; $i < @count($res_c); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res_c[$i] as $key => $value) {
                        }

                        // Extrai os dados do retorno
                        $veiculo       = $res_c[$i]['veiculo'];
                        $data_serv     = $res_c[$i]['data_serv'];
                        $data_contato  = $res_c[$i]['data_contato'];
                        $id            = $res_c[$i]['id'];

                        // Converte data do serviço de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data_serv = implode('/', array_reverse(explode('-', $data_serv)));

                        // Busca dados do veículo pelo ID
                        $query   = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                        $res     = $query->fetchAll(PDO::FETCH_ASSOC);
                        $marca   = $res[0]['marca'] . ' - ' . $res[0]['modelo'];
                        $placa   = $res[0]['placa'];
                        $cliente = $res[0]['cliente'];

                        // Busca dados do cliente pelo CPF
                        $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                        $res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cli  = $res_cat[0]['nome'];
                        $tel_cli   = $res_cat[0]['telefone'];
                        $email_cli = $res_cat[0]['email'];

                        /**
                         * Busca a última ordem de serviço do veículo
                         * (a mais recente, limitando a 1 registro)
                         */
                        $query_orc = $pdo->query("SELECT * FROM os where veiculo = '$veiculo' order by id desc limit 1 ");
                        $res_orc   = $query_orc->fetchAll(PDO::FETCH_ASSOC);
                        $descricao = $res_orc[0]['descricao'];
                    ?>

                        <!-- Linha da tabela para cada veículo com retorno pendente -->
                        <tr>
                            <td><?php echo $marca ?></td>
                            <td><?php echo $placa ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $tel_cli ?></td>
                            <td><?php echo $data_serv ?></td>
                            <td><?php echo $descricao ?></td>

                            <!-- Coluna de ações -->
                            <td>
                                <!-- Atualizar retorno (confirma que entrou em contato) -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=atualizar&id=<?php echo $id ?>"
                                   class='text-success mr-1'
                                   title='Atualizar Retorno'>
                                    <i class='fas fa-check'></i>
                                </a>

                                <!-- Enviar email promocional para o cliente -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=email&email=<?php echo $email_cli ?>&nome=<?php echo $nome_cli ?>"
                                   class='text-primary mr-1'
                                   title='Enviar Email'>
                                    <i class='far fa-envelope'></i>
                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
/**
 * ============================================================
 * LÓGICA DE AÇÕES (ATUALIZAR E ENVIAR EMAIL)
 * Processa antes da renderização da tabela
 * ============================================================
 */

/**
 * AÇÃO: ATUALIZAR RETORNO
 * Quando o usuário clica em "Atualizar Retorno",
 * atualiza a data_contato para a data atual
 */
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "atualizar") {
    $id_ret = $_GET["id"];

    // Atualiza a data do último contato para hoje
    $pdo->query("UPDATE retornos SET data_contato = curDate() WHERE id = '$id_ret'");

    // Redireciona para a mesma página (recarrega a lista)
    echo "<script language='javascript'> window.location = 'index.php?pag=$pag'; </script>";
}

/**
 * AÇÃO: ENVIAR EMAIL PROMOCIONAL
 * Quando o usuário clica em "Enviar Email",
 * dispara um email para o cliente com a mensagem de retorno
 */
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "email") {

    // Destinatário: email do cliente
    $destinatario = $_GET['email'];

    // Assunto: nome da oficina + indicação de promoção
    $assunto = utf8_decode($nome_oficina . ' - Promoção de Serviços');

    // Corpo do email: saudação personalizada + mensagem de retorno + endereço + telefone
    $mensagem = utf8_decode(
        'Olá ' . $_GET['nome'] . ', ' .
        $mensagem_retorno . "\n" .
        $endereco_oficina . "\n" .
        $telefone_oficina
    );

    // Cabeçalho: remetente (email do administrador)
    $cabecalhos = "From: " . $email_adm;

    // Envia o email (suprime erros com @)
    @mail($destinatario, $assunto, $mensagem, $cabecalhos);
}

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: EXCLUSÃO DE REGISTRO DE RETORNO
     (Mantido do original - atualmente sem modal de exclusão visível)
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";

        // Clique no botão de excluir
        $('#btn-deletar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/excluir.php",
                method: "post",
                data: $('form').serialize(),
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
     Ativa o plugin DataTables na tabela de retornos
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables com ordenação desabilitada
        $('#dataTable').dataTable({
            "ordering": false
        })
    });
</script>