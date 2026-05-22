<?php 
require_once("../conexao.php"); 
@session_start();
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$cpf_usuario = $_GET['cpf'];
//RECUPERAR DADOS DO USUÁRIO
$query = $pdo->query("SELECT * FROM usuarios where cpf = '$cpf_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_usu = @$res[0]['nome'];
$cpf_usu = @$res[0]['cpf'];
$email_usu = @$res[0]['email'];
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if($dataInicial != $dataFinal){
	$apuracao = $dataInicialF. ' até '. $dataFinalF;
}else{
	$apuracao = $dataInicialF;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Relatório de Comissões</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/relatorios.css">
</head>
<body>
	<div class="cabecalho">
		<div class="container">
			<div class="row titulos">
				<div class="col-sm-2 esquerda_float image">	
					<img src="../img/logo2.png" width="100px">
				</div>
				<div class="col-sm-10 esquerda_float">	
					<h2 class="titulo"><b><?php echo strtoupper($nome_oficina) ?></b></h2>
					<h6 class="subtitulo"><?php echo $endereco_oficina . ' Tel: '.$telefone_oficina  ?></h6>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-8 esquerda">	
				<span class="titulorel"> Relatório de Comissões - <?php echo $nome_usu ?> </span>
			</div>
			<div class="col-sm-4 direita" align="right">	
				<big> <small> Data: <?php echo $data_hoje; ?></small> </big>
			</div>
		</div>
		<hr>
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="esquerda_float margem-direita50">	
					<span class=""> <b> Período da Apuração </b> </span>
				</div>
				<div class="esquerda_float margem-direita50">	
					<span class=""> <?php echo $apuracao ?> </span>
				</div>	
			</div>
		</div>
		<hr>
		<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
			<tr bgcolor='#f9f9f9' >
				<th>Valor</th>
						<th>Serviço</th>
						<th>Tipo</th>
						<th>Data</th>
			</tr>
				<?php 
					$saldo = 0;
					$saldoF = 0;
					$query = $pdo->query("SELECT * FROM comissoes where mecanico = '$cpf_usuario' and data >= '$dataInicial' and data <= '$dataFinal' order by id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$valor = $res[$i]['valor'];
						$tipo = $res[$i]['tipo'];
						$servico = $res[$i]['id_servico'];
						$data = $res[$i]['data'];
						$saldo = $saldo + $valor;					
						$id = $res[$i]['id'];
						$data = implode('/', array_reverse(explode('-', $data)));
						$valorF = number_format($valor, 2, ',', '.');
						$saldoF = number_format($saldo, 2, ',', '.');
						if($tipo == 'Orçamento'){
						$query_cat = $pdo->query("SELECT * FROM orcamentos where id = '$servico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$id_servico = $res_cat[0]['servico'];
						$query_cat = $pdo->query("SELECT * FROM servicos where id = '$id_servico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_servico = $res_cat[0]['nome'];
						}else{
							$query_cat = $pdo->query("SELECT * FROM os where id = '$servico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_servico = $res_cat[0]['descricao'];
						}
						?>
						<tr>					
							<td>R$ <?php echo $valorF ?></td>
							<td><?php echo $nome_servico ?></td>
							<td><?php echo $tipo ?></td>
							<td><?php echo $data ?></td>
						</tr>
					<?php } ?>
		</table>
		<hr>
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">								
					<span class="areaTotal"> <b> Total : R$ <?php echo $saldoF ?> </b> </span>
				</div>
			</div>
		</div>
		<hr>
	</div>
	<div class="footer">
		<p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p> 
	</div>
</body>
</html>
