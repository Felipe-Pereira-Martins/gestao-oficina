<?php
/**
 * ============================================================
 * RELATÓRIO DE SERVIÇOS — HTML
 * Gera relatório de ordens de serviço filtrado por período
 * e status (concluído/pendente/todos)
 * ============================================================
 */
// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");
// Inicia a sessão (necessário para permissões de acesso)
@session_start();
/**
 * Configuração de localidade para datas em português
 * Define o idioma e timezone para São Paulo
 */
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
// Data de hoje formatada por extenso (ex: QUARTA-FEIRA, 13 DE MAIO DE 2026)
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));
/**
 * Captura os parâmetros do filtro vindos da URL
 * Enviados pelo formulário de relatórios (modal-relatorios.php)
 */
$dataInicial = $_GET['dataInicial'];  // Data inicial do filtro (YYYY-MM-DD)
$dataFinal   = $_GET['dataFinal'];    // Data final do filtro (YYYY-MM-DD)
$status      = $_GET['status'];       // Status: 'Sim' (concluído), 'Não' (pendente) ou '' (todos)
// Prepara o status para uso com LIKE na query SQL
$status_like = '%' . $status . '%';
// Converte datas para formato brasileiro (DD/MM/YYYY)
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF   = implode('/', array_reverse(explode('-', $dataFinal)));
/**
 * Define o texto do status para exibição no título do relatório
 */
if ($status == 'Sim') {
	$status_serv = 'Concluídos';
} else if ($status == 'Não') {
	$status_serv = 'Pendentes';
} else {
	$status_serv = '';  // Todos (sem filtro de status)
}
/**
 * Define o texto do período de apuração
 * Se data inicial = data final, mostra apenas uma data
 * Caso contrário, mostra o intervalo
 */
if ($dataInicial != $dataFinal) {
	$apuracao = $dataInicialF . ' até ' . $dataFinalF;
} else {
	$apuracao = $dataInicialF;
}
// Fallback seguro para variáveis que podem estar comentadas no config.php
$endereco_oficina  = isset($endereco_oficina) ? $endereco_oficina : '';
$rodape_relatorios = isset($rodape_relatorios) ? $rodape_relatorios : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>Relatório de Serviços</title>
	<!-- Bootstrap 3 para estilização do relatório -->
	<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
		integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
		crossorigin="anonymous">
		<link rel="stylesheet" href="../css/relatorios.css">
</head>
<body>
	<!-- ═══════════════════════════════════════════════════
         CABEÇALHO DO RELATÓRIO
         (Logo e nome da oficina comentados para revisão futura)
         ═══════════════════════════════════════════════════ -->
	<div class="cabecalho">
		<div class="container">
			<div class="row titulos">
				<!--
                    Logo da oficina — será reimplementado em nova versão
                    <div class="col-sm-2 esquerda_float image">
                        <img src="../img/logo2.png" width="100px">
                    </div>
                -->
				<!--
                    Nome e endereço da oficina — será reimplementado em nova versão
                    <div class="col-sm-10 esquerda_float">
                        <h2 class="titulo"><b><?php echo strtoupper($nome_oficina) ?></b></h2>
                        <h6 class="subtitulo"><?php echo $endereco_oficina . ' Tel: ' . $telefone_oficina ?></h6>
                    </div>
                -->
			</div>
		</div>
	</div>
	<!-- ═══════════════════════════════════════════════════
         CONTEÚDO PRINCIPAL DO RELATÓRIO
         ═══════════════════════════════════════════════════ -->
	<div class="container">
		<!-- ── TÍTULO E DATA DO RELATÓRIO ── -->
		<div class="row">
			<!-- Título: "Relatório de Serviços" + status (se houver filtro) -->
			<div class="col-sm-8 esquerda">
				<span class="titulorel"> Relatório de Serviços <?php echo $status_serv ?> </span>
			</div>
			<!-- Data de emissão do relatório -->
			<div class="col-sm-4 direita" align="right">
				<big><small> Data: <?php echo $data_hoje; ?></small></big>
			</div>
		</div>
		<hr>
		<!-- ── PERÍODO DA APURAÇÃO ── -->
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="esquerda_float margem-direita50">
					<span class=""><b> Período da Apuração </b></span>
				</div>
				<div class="esquerda_float margem-direita50">
					<span class=""> <?php echo $apuracao ?> </span>
				</div>
			</div>
		</div>
		<hr>
		<!-- ═══════════════════════════════════════════════
             TABELA DE SERVIÇOS
             ═══════════════════════════════════════════════ -->
		<table class="table" width="100%" cellspacing="0" cellpadding="3">
			<!-- Cabeçalho da tabela -->
			<tr bgcolor="#f9f9f9">
				<th><b>Cliente</b></th>
				<th><b>Mecânico</b></th>
				<th><b>Valor Serviço</b></th>
				<th><b>Serviço</b></th>
				<th><b>Veículo</b></th>
				<th><b>Data Entrega</b></th>
				<th><b>Concluído</b></th>
			</tr>
			<?php
			/**
			 * Busca as ordens de serviço conforme o filtro aplicado
			 * Filtra por período (data inicial até data final)
			 * e por status (concluído/pendente/todos)
			 */
			$totalValores  = 0;
			$totalValoresF = 0;
			$query = $pdo->query("SELECT * FROM os where data >= '$dataInicial' and data <= '$dataFinal' and concluido LIKE '$status_like' order by data asc");
			$res   = $query->fetchAll(PDO::FETCH_ASSOC);
			// Percorre cada OS retornada
			for ($i = 0; $i < @count($res); $i++) {
				// Itera pelas chaves do registro (mantido do original, sem efeito prático)
				foreach ($res[$i] as $key => $value) {
				}
				// Extrai os dados da OS
				$cliente        = $res[$i]['cliente'];
				$veiculo        = $res[$i]['veiculo'];
				$descricao      = $res[$i]['descricao'];
				$valor          = $res[$i]['valor'];
				$valor_mao_obra = $res[$i]['valor_mao_obra'];
				$data           = $res[$i]['data'];
				$data_entrega   = $res[$i]['data_entrega'];
				$concluido      = $res[$i]['concluido'];
				$mecanico       = $res[$i]['mecanico'];
				$tipo           = $res[$i]['tipo'];
				$id             = $res[$i]['id'];
				// Acumula o valor total e formata
				$totalValores  = $valor + $totalValores;
				$totalValoresF = number_format($totalValores, 2, ',', '.');
				// Converte datas para formato brasileiro
				$data         = implode('/', array_reverse(explode('-', $data)));
				$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
				// Formata valores monetários
				$valor          = number_format($valor, 2, ',', '.');
				$valor_mao_obra = number_format($valor_mao_obra, 2, ',', '.');
				// Busca o nome e email do cliente pelo CPF
				$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
				$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$nome_cli  = $res_cat[0]['nome'];
				$email_cli = $res_cat[0]['email'];
				// Busca o nome do mecânico pelo CPF (primeira consulta)
				$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
				$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$nome_mec  = $res_cat[0]['nome'];
				// Busca marca e modelo do veículo pelo ID
				$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
				$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$modelo    = $res_cat[0]['modelo'];
				$marca     = $res_cat[0]['marca'];
				// Busca o nome do mecânico novamente (consulta duplicada mantida do original)
				$query_cat     = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
				$res_cat       = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$nome_mecanico = $res_cat[0]['nome'];
				// Define a cor do status (não utilizada neste relatório, mantida do original)
				if ($concluido == 'Sim') {
					$cor_pago = 'text-success';
				} else {
					$cor_pago = 'text-danger';
				}
			?>
				<!-- Linha da tabela para cada OS -->
				<tr>
					<td><?php echo $nome_cli ?></td>
					<td><?php echo $nome_mec ?></td>
					<td>R$ <?php echo $valor ?></td>
					<td><?php echo $descricao ?></td>
					<td><?php echo $marca . ' ' . $modelo ?></td>
					<td><?php echo $data_entrega ?></td>
					<td><?php echo $concluido ?></td>
				</tr>
			<?php } ?>
		</table>
		<hr>
		<!-- ── TOTAL DE SERVIÇOS ── -->
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">
					<span class="areaTotal">
						<b> Total de Serviços : R$<?php echo $totalValoresF ?> </b>
					</span>
				</div>
			</div>
		</div>
		<hr>
	</div>
	<!-- ═══════════════════════════════════════════════════
         RODAPÉ DO RELATÓRIO
         (Comentado para revisão futura)
         ═══════════════════════════════════════════════════ -->
	<!--
    <div class="footer">
        <p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p>
    </div>
    -->
</body>
</html>