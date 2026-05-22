<?php
/**
 * ============================================================
 * RELATÓRIO DE COMPRAS
 * ============================================================
 */
require_once("../conexao.php");
@session_start();

// Configuração de localidade e data
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));

// Parâmetros do filtro
$dataInicial  = $_GET['dataInicial'];
$dataFinal    = $_GET['dataFinal'];
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF   = implode('/', array_reverse(explode('-', $dataFinal)));

// Período de apuração
$apuracao = ($dataInicial != $dataFinal)
    ? $dataInicialF . ' até ' . $dataFinalF
    : $dataInicialF;

// Configuração do template
$titulo_rel    = 'Relatório de Compras';
$subtitulo_rel = '';
$total_label   = 'Total';
$total_valor   = '0,00';

// Inclui o layout base
require_once("_layout.php");
?>

<!-- TABELA DE COMPRAS -->
<table class="table" width="100%" cellspacing="0" cellpadding="3">
    <tr bgcolor="#f9f9f9">
        <th>Produto</th>
        <th>Valor</th>
        <th>Funcionário</th>
        <th>Data</th>
    </tr>

    <?php
    $saldo  = 0;
    $saldoF = 0;

    $query = $pdo->query("SELECT * FROM compras where data >= '$dataInicial' and data <= '$dataFinal' order by data asc, id asc");
    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < @count($res); $i++) {
        foreach ($res[$i] as $key => $value) {}
        $produto     = $res[$i]['produto'];
        $valor       = $res[$i]['valor'];
        $funcionario = $res[$i]['funcionario'];
        $data        = $res[$i]['data'];
        $saldo  = $saldo + $valor;
        $saldoF = number_format($saldo, 2, ',', '.');
        $query_prod   = $pdo->query("SELECT * FROM produtos where id = '$produto' ");
        $res_prod     = $query_prod->fetchAll(PDO::FETCH_ASSOC);
        $nome_produto = $res_prod[0]['nome'];
        $query_usu        = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario' ");
        $res_usu          = $query_usu->fetchAll(PDO::FETCH_ASSOC);
        $nome_funcionario = $res_usu[0]['nome'];
        $valorF = number_format($valor, 2, ',', '.');
        $data   = implode('/', array_reverse(explode('-', $data)));
    ?>
        <tr>
            <td><?php echo $nome_produto ?></td>
            <td>R$ <?php echo $valorF ?></td>
            <td><?php echo $nome_funcionario ?></td>
            <td><?php echo $data ?></td>
        </tr>
    <?php } ?>

</table>

<?php
// Atualiza o total e inclui o rodapé
$total_valor = $saldoF;
require_once("_footer.php");
?>