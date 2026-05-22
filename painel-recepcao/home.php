<?php
/**
 * ============================================================
 * HOME / DASHBOARD — PAINEL RECEPÇÃO
 * Exibe cards com resumo financeiro e indicadores da oficina
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

/**
 * ============================================================
 * CÁLCULO DAS DATAS BASE
 * ============================================================
 */

// Data de hoje no formato MySQL (YYYY-MM-DD)
$hoje = date('Y-m-d');

// Mês e ano atuais para cálculo do início do mês
$mes_atual = Date('m');
$ano_atual = Date('Y');

// Primeiro dia do mês atual (ex: 2026-05-01)
$dataInicioMes = $ano_atual . "-" . $mes_atual . "-01";

/**
 * ============================================================
 * TOTAIS DE ORÇAMENTOS E ENTREGAS
 * ============================================================
 */

// Total de orçamentos concluídos no mês atual
$query_cat       = $pdo->query("SELECT * FROM orcamentos where status = 'Concluído' and data >= '$dataInicioMes' and data <= curDate()");
$res_cat         = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalConcluidos = @count($res_cat);

// Total de orçamentos pendentes (abertos) no mês atual
$query_cat      = $pdo->query("SELECT * FROM orcamentos where status = 'Aberto' and data >= '$dataInicioMes' and data <= curDate() ");
$res_cat        = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalPendentes = @count($res_cat);

// Total de orçamentos aprovados no mês atual
$query_cat      = $pdo->query("SELECT * FROM orcamentos where status = 'Aprovado' and data >= '$dataInicioMes' and data <= curDate() ");
$res_cat        = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalAprovados = @count($res_cat);

// Total de veículos com entrega prevista para hoje
$query_cat     = $pdo->query("SELECT * FROM os where data_entrega = curDate()  ");
$res_cat       = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalEntregas = @count($res_cat);

/**
 * ============================================================
 * TOTAL DE COMISSÕES DO DIA (MECÂNICO LOGADO)
 * ============================================================
 */

$totalComissoesHoje = 0;

// Busca comissões do dia para o mecânico da sessão atual
$query_cat = $pdo->query("SELECT * FROM comissoes where data = curDate() and mecanico = '$_SESSION[cpf_usuario]' ");
$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);

// Percorre cada comissão do dia
for ($i = 0; $i < @count($res_cat); $i++) {

    // Itera pelas chaves do registro (mantido do original, sem efeito prático)
    foreach ($res_cat[$i] as $key => $value) {
    }

    $valor               = $res_cat[$i]['valor'];
    $totalComissoesHoje  = $totalComissoesHoje + $valor;
}

// Formata valor total das comissões (ex: 150.00 → 150,00)
$totalComissoesHoje = number_format($totalComissoesHoje, 2, ',', '.');

/**
 * ============================================================
 * TOTALIZA MOVIMENTAÇÕES DO DIA
 * Calcula entradas, saídas e saldo do dia atual
 * ============================================================
 */

// Inicializa variáveis de totais do dia
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

// Calcula o saldo do dia (entradas - saídas)
$saldo = $entradas - $saidas;

// Define cor do saldo e borda do card:
// Verde se positivo | Vermelho se negativo
if ($saldo < 0) {
    $corTotal  = 'text-danger';
    $corTotal2 = 'border-left-danger';
} else {
    $corTotal  = 'text-success';
    $corTotal2 = 'border-left-success';
}

// Formata valores monetários (ex: 1500.00 → 1.500,00)
$entradas = number_format($entradas, 2, ',', '.');
$saidas   = number_format($saidas, 2, ',', '.');
$saldo    = number_format($saldo, 2, ',', '.');

/**
 * ============================================================
 * TOTALIZA MOVIMENTAÇÕES DO MÊS
 * Calcula entradas, saídas e saldo do mês atual
 * ============================================================
 */

// Inicializa variáveis de totais do mês
$saldoMes    = 0;
$entradasMes = 0;
$saidasMes   = 0;

// Busca todas as movimentações do primeiro dia do mês até hoje
$query = $pdo->query("SELECT * FROM movimentacoes where data >= '$dataInicioMes' and data <= curDate()");
$res   = $query->fetchAll(PDO::FETCH_ASSOC);

// Percorre cada movimentação do mês
for ($i = 0; $i < @count($res); $i++) {

    // Itera pelas chaves do registro (mantido do original, sem efeito prático)
    foreach ($res[$i] as $key => $value) {
    }

    $valor = $res[$i]['valor'];
    $tipo  = $res[$i]['tipo'];

    // Acumula entradas ou saídas conforme o tipo
    if ($tipo == 'Entrada') {
        $entradasMes = $entradasMes + $valor;
    } else {
        $saidasMes = $saidasMes + $valor;
    }
}

// Calcula o saldo do mês (entradas - saídas)
$saldoMes = $entradasMes - $saidasMes;

// Define cor do saldo e borda do card:
// Verde se positivo | Vermelho se negativo
if ($saldoMes < 0) {
    $corTotalMes   = 'text-danger';
    $corTotal2Mes  = 'border-left-danger';
} else {
    $corTotalMes   = 'text-success';
    $corTotal2Mes  = 'border-left-success';
}

// Formata valores monetários (ex: 1500.00 → 1.500,00)
$entradasMes = number_format($entradasMes, 2, ',', '.');
$saidasMes   = number_format($saidasMes, 2, ',', '.');
$saldoMes    = number_format($saldoMes, 2, ',', '.');

?>

<!-- ═══════════════════════════════════════════════════════
     LINHA 1: CARDS FINANCEIROS (MOVIMENTAÇÕES)
     ═══════════════════════════════════════════════════════ -->
<div class="row">

    <!-- Card: Entradas do Dia -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Entradas do Dia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$entradas ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Saídas do Dia -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Saídas do Dia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$saidas ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Saldo do Dia (cor dinâmica conforme positivo/negativo) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card <?php echo $corTotal2 ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold <?php echo $corTotal ?> text-uppercase mb-1">
                            Saldo do Dia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php echo @$saldo ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x <?php echo $corTotal ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Saldo do Mês (cor dinâmica conforme positivo/negativo) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card <?php echo $corTotal2Mes ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold <?php echo $corTotalMes ?> text-uppercase mb-1">
                            Saldo do Mês
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?php echo @$saldoMes ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x <?php echo $corTotalMes ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- ═══════════════════════════════════════════════════════
     LINHA 2: CARDS DE ORÇAMENTOS E ENTREGAS
     ═══════════════════════════════════════════════════════ -->
<div class="row">

    <!-- Card: Orçamentos Concluídos (mês) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Orçamentos Concluídos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$totalConcluidos ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Orçamentos Pendentes (mês) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Orçamentos Pendentes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$totalPendentes ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Orçamentos Aprovados (mês) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Orçamentos Aprovados
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$totalAprovados ?>
                        </div>
                    </div>
                    <div class="col-auto" align="center">
                        <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Entregas de Veículos (hoje) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Entregas Veículos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo @$totalEntregas ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>