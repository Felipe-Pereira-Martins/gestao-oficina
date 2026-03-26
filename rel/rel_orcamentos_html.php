<?php 
require_once("../conexao.php"); 
@session_start();


setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$status = $_GET['status'];

$status_like = '%'.$status.'%';

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if($status == 'Aberto'){
	$status_serv = 'Aberto';
}else if($status == 'Aprovado'){
	$status_serv = 'Aprovado';
}else if($status == 'Concluído'){
	$status_serv = 'Concluído';
}else{
	$status_serv = '';
}


if($dataInicial != $dataFinal){
	$apuracao = $dataInicialF. ' até '. $dataFinalF;
}else{
	$apuracao = $dataInicialF;
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Relatório de Orçamentos</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<style>

		@page {
			margin: 0px;

		}

		.footer {
			margin-top:20px;
			width:100%;
			background-color: #ebebeb;
			padding:10px;
			position:relative;
			bottom:0;
		}

		.cabecalho {    
			background-color: #ebebeb;
			padding:10px;
			margin-bottom:30px;
			width:100%;
			height:100px;
		}

		.titulo{
			margin:0;
			font-size:28px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;

		}

		.subtitulo{
			margin:0;
			font-size:17px;
			font-family:Arial, Helvetica, sans-serif;
		}

		.areaTotais{
			border : 0.5px solid #bcbcbc;
			padding: 15px;
			border-radius: 5px;
			margin-right:25px;
			margin-left:25px;
			position:absolute;
			right:20;
		}

		.areaTotal{
			border : 0.5px solid #bcbcbc;
			padding: 15px;
			border-radius: 5px;
			margin-right:25px;
			margin-left:25px;
			background-color: #f9f9f9;
			margin-top:2px;
		}

		.pgto{
			margin:1px;
		}

		.fonte13{
			font-size:13px;
		}

		.esquerda{
			display:inline;
			width:50%;
			float:left;
		}

		.direita{
			display:inline;
			width:50%;
			float:right;
		}

		.table{
			padding:15px;
			font-family:Verdana, sans-serif;
			margin-top:20px;
		}

		.texto-tabela{
			font-size:12px;
		}


		.esquerda_float{

			margin-bottom:10px;
			float:left;
			display:inline;
		}


		.titulos{
			margin-top:10px;
		}

		.image{
			margin-top:-10px;
		}

		.margem-direita{
			margin-right: 80px;
		}

		.margem-direita50{
			margin-right: 50px;
		}

		hr{
			margin:8px;
			padding:1px;
		}


		.titulorel{
			margin:0;
			font-size:28px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;

		}

		.margem-superior{
			margin-top:30px;
		}


	</style>

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
				<span class="titulorel"> Relatório de Orçamentos <?php echo $status_serv ?> </span>
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
				<th><b>Cliente</b></th>
						<th><b>Veículo</b></th>
						<th><b>Valor</b></th>
						<th><b>Serviço</b></th>
						<th><b>Data</b></th>
						<th><b>Mecânico</b></th>
						<th><b>Status</b></th>

			</tr>

			<?php 
					$totalValores = 0;
					$total_prod = 0;
					$total_prod_mao = 0;
					$totalValoresF = 0;
					$query = $pdo->query("SELECT * FROM orcamentos where data >= '$dataInicial' and data <= '$dataFinal' and status LIKE '$status_like' order by data asc, id asc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cliente = $res[$i]['cliente'];
						$veiculo = $res[$i]['veiculo'];
						$descricao = $res[$i]['descricao'];
						$valor = $res[$i]['valor'];
						$servico = $res[$i]['servico'];
						$data = $res[$i]['data'];
						$data_entrega = $res[$i]['data_entrega'];
						$garantia = $res[$i]['garantia'];
						$mecanico = $res[$i]['mecanico'];
						$status = $res[$i]['status'];
						$id = $res[$i]['id'];


						$query_p = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id' ");
						$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);

						for ($i2=0; $i2 < @count($res_p); $i2++) { 
							foreach ($res_p[$i2] as $key => $value) {
							}
							$prod = $res_p[$i2]['produto'];

							$query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
							$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
						
							$valor_prod = $res_pro[0]['valor_venda'];
							$total_prod = $valor_prod + $total_prod;
						}

						$total_prod_mao = $total_prod + $valor;
						$totalValores = $total_prod_mao + $totalValores;
						$totalValoresF = number_format($totalValores, 2, ',', '.');
						$total_prod_maoF = number_format($total_prod_mao, 2, ',', '.');

						$data = implode('/', array_reverse(explode('-', $data)));
						$valor = number_format($valor, 2, ',', '.');


						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];

						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res_cat[0]['modelo'];
						$marca = $res_cat[0]['marca'];

						$query_cat = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_serv = $res_cat[0]['nome'];

						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];

						if($status == 'Aberto'){
							$cor_pago = 'text-danger';
						}else if($status == 'Aprovado'){
							$cor_pago = 'text-primary';
						}else{
							$cor_pago = 'text-success';
						}




						?>

						<tr>
							<td>
								
								<?php echo $nome_cli ?>
									
								</td>
							<td><?php echo $marca .' '.$modelo ?></td>
							<td>R$ <?php echo $total_prod_maoF ?></td>
							<td><?php echo $nome_serv ?></td>
							<td><?php echo $data ?></td>
							<td><?php echo $nome_mecanico ?></td>
							<td><?php echo $status ?></td>
							
							
						</tr>
					<?php } ?>



		</table>

		<hr>


		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">	
					<span class="areaTotal"> <b> Total de Serviços : R$ <?php echo $totalValoresF ?> </b> </span>
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
