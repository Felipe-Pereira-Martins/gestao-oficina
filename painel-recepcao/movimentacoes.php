<?php
/**
 * ============================================================
 * MOVIMENTAÇÕES (CAIXA) — PAINEL RECEPÇÃO
 * Listagem de entradas e saídas do dia com totais
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links
$pag = "movimentacoes";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

/**
 * ============================================================
 * TOTALIZA MOVIMENTAÇÕES DO DIA
 * Calcula entradas, saídas e saldo do dia atual
 * ============================================================
 */

// Inicializa variáveis de totais
$saldo    = 0;
$entradas = 0;
$saidas   = 0;

// Busca todas as movimentações do dia atual
$query = $pdo->query("SELECT * FROM movimentacoes where data = curDate()");
$res   = $query->fetchAll(PDO::FETCH_ASSOC);

// Percorre cada movimentação do dia
for ($i = 0; $i < @count($res); $i++) {

    // Itera pelas chaves do registro (mantido do original, sem efeito prático)
    foreach ($res[$i] as $key => $value) {
    }

    $valor = $res[$i]['valor'];
    $tipo  = $res[$i]['tipo'];

    // Acumula entradas ou saídas conforme o tipo
    if ($tipo == 'Entrada') {
        $entradas = $entradas + $valor;
    } else {
        $saidas = $saidas + $valor;
    }
}

// Calcula o saldo (entradas - saídas)
$saldo = $entradas - $saidas;

// Define a cor do saldo:
// Verde se positivo | Vermelho se negativo
if ($saldo < 0) {
    $corTotal = 'text-danger';
} else {
    $corTotal = 'text-success';
}

// Formata valores monetários (ex: 1500.00 → 1.500,00)
$entradas = number_format($entradas, 2, ',', '.');
$saidas   = number_format($saidas, 2, ',', '.');
$saldo    = number_format($saldo, 2, ',', '.');
?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE MOVIMENTAÇÕES (DataTables)
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
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Funcionário</th>
                        <th>Data</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todas as movimentações
                     * Ordenadas por ID (mais recentes primeiro)
                     */
                    $query = $pdo->query("SELECT * FROM movimentacoes order by id desc ");
                    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada movimentação retornada
                    for ($i = 0; $i < @count($res); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res[$i] as $key => $value) {
                        }

                        // Extrai os dados da movimentação
                        $descricao   = $res[$i]['descricao'];
                        $tipo        = $res[$i]['tipo'];
                        $funcionario = $res[$i]['funcionario'];
                        $data        = $res[$i]['data'];
                        $valor       = $res[$i]['valor'];
                        $id          = $res[$i]['id'];

                        // Busca o nome do funcionário pelo CPF
                        $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario'");
                        $res_usu   = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                        $nome_func = $res_usu[0]['nome'];

                        // Formata valor monetário (ex: 1500.00 → 1.500,00)
                        $valor = number_format($valor, 2, ',', '.');

                        // Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data = implode('/', array_reverse(explode('-', $data)));

                        // Define a cor do tipo:
                        // Verde = Entrada | Vermelho = Saída
                        if ($tipo == 'Entrada') {
                            $cor_pago = 'text-success';
                        } else {
                            $cor_pago = 'text-danger';
                        }
                    ?>

                        <!-- Linha da tabela para cada movimentação -->
                        <tr>
                            <!-- Tipo com indicador colorido -->
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
                                <?php echo $tipo ?>
                            </td>
                            <td><?php echo $descricao ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $nome_func ?></td>
                            <td><?php echo $data ?></td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>

        <!-- ── RESUMO DO DIA ── -->
        <!-- Linha 1: Entradas e Saídas -->
        <div class="row mt-4 ml-1">
            <span>
                Entradas Dia:
                <span class="text-success">R$ <?php echo $entradas ?></span>
            </span>

            <span class="ml-4">
                Saídas Dia:
                <span class="text-danger">R$ <?php echo $saidas ?></span>
            </span>
        </div>

        <!-- Linha 2: Saldo do Dia (alinhado à direita) -->
        <div align="right">
            Saldo Dia:
            <span class="<?php echo $corTotal ?>">R$ <?php echo $saldo ?></span>
        </div>

    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de movimentações
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables com ordenação desabilitada
        $('#dataTable').dataTable({
            "ordering": false
        })
    });
</script>