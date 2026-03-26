<?php 
require_once("../conexao.php"); 
@session_start();

$id = $_GET['id'];

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));


//DADOS DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM os where veiculo = '$id' order by id desc limit 1 ");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $res_orc[0]['cliente'];
$veiculo = $res_orc[0]['veiculo'];
$descricao = $res_orc[0]['descricao'];
$obs = $res_orc[0]['obs'];
$valor_orc = $res_orc[0]['valor_mao_obra'];
$valor = $res_orc[0]['valor'];
$mecanico = $res_orc[0]['mecanico'];
$data_orc = $res_orc[0]['data'];
$data_entrega = $res_orc[0]['data_entrega'];
$tipo = $res_orc[0]['tipo'];
$id_orc = $res_orc[0]['id_orc'];
$concluido = $res_orc[0]['concluido'];
$data = $res_orc[0]['data'];

$garantia = $res_orc[0]['garantia'];

$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
$data = implode('/', array_reverse(explode('-', $data)));

$valor_orc_f = number_format($valor_orc, 2, ',', '.');
$valor_f = number_format($valor, 2, ',', '.');

$query_mec = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
$res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
$nome_mecanico = $res_mec[0]['nome'];


$query_cli = $pdo->query("SELECT * FROM clientes where cpf = '$cpf_cliente' ");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res_cli[0]['nome'];
$telefone_cli = $res_cli[0]['telefone'];
$endereco_cli = $res_cli[0]['endereco'];
$email_cli = $res_cli[0]['email'];


$query_vei = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);
$marca = $res_vei[0]['marca'] . ' - ' .$res_vei[0]['modelo'];
$placa = $res_vei[0]['placa'];
$cor = $res_vei[0]['cor'];
$ano = $res_vei[0]['ano'];
$km = $res_vei[0]['km'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Relatório de OS</title>
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

		hr{
			margin:8px;
			padding:1px;
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
				<big> Ordem de Serviço Nº <?php echo $id ?> Concluída : <?php echo $concluido ?>  </big>
			</div>
			<div class="col-sm-4 direita" align="right">	
				<big> <small> Data da Geração da OS: <?php echo $data; ?></small> </big>
			</div>
		</div>


		<hr>



		<div class="row">
			<div class="col-sm-12">
				<p class="fonte13"> <b> Dados do Cliente </b> </p>
			</div>
		</div>

		<div class="row">
			<div class="esquerda">
				<div class="col-sm-6">
					<p class="fonte13">  Nome: <?php echo $nome_cli; ?> </p>

					<p class="fonte13">  Email: <?php echo $email_cli; ?> </p>

					<p class="fonte13">  Endereço: <?php echo $endereco_cli; ?> </p>
				</div>
				
			</div>

			<div class="direita">
				<div class="col-sm-6">
					<p class="fonte13">  Telefone: <?php echo $telefone_cli; ?> </p>
					<p class="fonte13">  CPF: <?php echo $cpf_cliente; ?> </p>
					<p class="fonte13"> &nbsp;&nbsp;  </p>
				</div>
			</div>
		</div>



		<hr>


		<div class="row">
			<div class="col-sm-12">
				<p class="fonte13"> <b> Dados do Veículo </b> </p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="esquerda_float margem-direita">
					<p class="fonte13 ">  Marca / Modelo: <?php echo $marca; ?> </p>
				</div>
				<div class="esquerda_float margem-direita">
					<p class="fonte13">  Placa: <?php echo $placa; ?> Cor: <?php echo $cor ?> </p>
				</div>
				<div class="">
					<p class="fonte13">  Ano: <?php echo $ano ?> KM: <?php echo $km ?> </p>

				</div>


			</div>
		</div>

		
				<div class="">
					<p class="fonte13">  Observações: <?php echo $obs; ?> </p>

				</div>

		<hr>



		<div class="row ">
			<div class="col-sm-12">
				<p style="font-size:14px"> <b> Dados do Serviço </b> </p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">	
				<p style="font-size:13px"> <b> Tipo de Serviço: </b> <?php echo $descricao; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($garantia > 0){ ?>
						<b>Garantia: </b> <?php echo $garantia ?> Dias <?php } ?> </p>
						
					</div>


				</div>




				<?php 
				if($tipo == 'Orçamento'){
				$total_prod = 0;
				$valor_prod_f = 0;
				$total_prod_f = 0;
				$total_pagar_f = 0;
				$query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id_orc' ");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res) > 0){
					?>

					<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
						<tr bgcolor='#f9f9f9' >
							<td> <b>Peça / Produto</b> </td>
							<td> <b>Valor</b> </td>
							<td> <b> Quantidade</b> </td>

						</tr>
						<?php 


						for ($i=0; $i < @count($res); $i++) { 
							foreach ($res[$i] as $key => $value) {
							}
							$prod = $res[$i]['produto'];

							$query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
							$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
							$nome_prod = $res_pro[0]['nome'];
							$valor_prod = $res_pro[0]['valor_venda'];
							$id_prd = $res_pro[0]['id'];

							$total_prod = $valor_prod + $total_prod;
							$total_pagar = $total_prod + $valor_orc;

							$valor_prod_f = number_format($valor_prod, 2, ',', '.');
							$total_prod_f = number_format($total_prod, 2, ',', '.');
							$total_pagar_f = number_format($total_pagar, 2, ',', '.');

							?>

							<tr>
								<td> <?php echo $nome_prod; ?> </td>
								<td>R$ <?php echo $valor_prod_f; ?> </td>
								<td> 1 </td>

							</tr>

						<?php } ?>

					</table>
				<?php }else{
					$total_pagar = $valor_orc;
					$total_pagar_f = number_format($total_pagar, 2, ',', '.');

				} } ?>

				<hr>
				




				<div class="row">
					<div class="col-md-6">	

						<?php if($tipo == 'Orçamento'){ ?>
						<p style="font-size:13px">  <b>Valor Peças / Produtos: </b> R$ <?php echo $total_prod_f; ?> </p><?php } ?>
						<p style="font-size:13px">  <b>Valor Mão de Obra: </b> R$ <?php echo $valor_orc_f; ?> </p>
						<p style="font-size:13px">  <b>Mecânico: </b> <?php echo $nome_mecanico; ?>  </p>
						<p style="font-size:13px">  <b>Concluída: </b> <?php echo $concluido; ?>  </p>
						

					</div>
					<div class="col-md-4 areaTotal" align="right">

						<?php if($tipo == 'Orçamento'){ ?>
						<p class="pgto" style="font-size:16px">  <b>
							<?php if($concluido == 'Sim'){ ?>
								Total Pago: 
								<?php }else{ ?>
								 Total à Pagar: 
								 <?php } ?> </b> R$ <?php echo $total_pagar_f; ?>  </p>
						<p class="pgto" style="font-size:12px">  Previsão de Entrega: <?php echo $data_entrega; ?> <br> 
							 </p>
							<?php }else{ ?>

								<p class="pgto" style="font-size:16px">  <b><?php if($concluido == 'Sim'){ ?>
								Total Pago: 
								<?php }else{ ?>
								 Total à Pagar: 
								 <?php } ?> </b> R$ <?php echo $valor_orc_f; ?>  </p>
						<p class="pgto" style="font-size:12px">  Previsão de Entrega: <?php echo $data_entrega; ?> <br> 
							 </p>

							<?php } ?>

							</div>


						</div>



						<br><br><br>





					</div>


					<div class="footer">
		<p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p> 
	</div>




				</body>
				</html>
