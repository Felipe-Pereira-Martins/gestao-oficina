<?php 
require_once("../conexao.php"); 
@session_start();


setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));

$id = $_GET['id'];

$query_vei = $pdo->query("SELECT * FROM veiculos where id = '$id' ");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);
$marca = $res_vei[0]['marca'] . ' - ' .$res_vei[0]['modelo'];
$placa = $res_vei[0]['placa'];
$cor = $res_vei[0]['cor'];
$ano = $res_vei[0]['ano'];
$km = $res_vei[0]['km'];
$cliente = $res_vei[0]['cliente'];

$query_cli = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res_cli[0]['nome'];
$telefone_cli = $res_cli[0]['telefone'];
$endereco_cli = $res_cli[0]['endereco'];
$email_cli = $res_cli[0]['email'];




?>

<!DOCTYPE html>
<html>
<head>
	<title>Serviços do Veículo - <?php echo $marca ?></title>
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
				<span class="titulorel"> Serviços do Veículo <?php echo $marca ?> </span>
			</div>
			<div class="col-sm-4 direita" align="right">	
				<big> <small> Data: <?php echo $data_hoje; ?></small> </big>
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
					<p class="fonte13">  CPF: <?php echo $cliente; ?> </p>
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
					<p class="fonte13">  Placa: <?php echo $placa; ?>  </p>
				</div>
				<div class="esquerda_float margem-direita">
					<p class="fonte13">  Cor: <?php echo $cor ?> </p>
				</div>
				<div class="">
					<p class="fonte13">  Ano: <?php echo $ano ?> KM: <?php echo $km ?> </p>

				</div>


			</div>
		</div>



		<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
			<tr bgcolor='#f9f9f9' >
						<th>Serviço</th>
						<th>Mão de Obra</th>
						<th>Produtos / Peças</th>
						<th>Total Serviço</th>
						<th>Data</th>
						<th>Garantia</th>
						<th>Data Entrega</th>

			</tr>

			<?php 
					$totalValores = 0;
					$totalValoresF = 0;
					$query = $pdo->query("SELECT * FROM os where veiculo = '$id' order by id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cliente = $res[$i]['cliente'];
						$veiculo = $res[$i]['veiculo'];
						$descricao = $res[$i]['descricao'];
						$valor = $res[$i]['valor'];
						$valor_mao_obra = $res[$i]['valor_mao_obra'];
						$garantia = $res[$i]['garantia'];
						$data = $res[$i]['data'];
						$data_entrega = $res[$i]['data_entrega'];
						$concluido = $res[$i]['concluido'];
						$mecanico = $res[$i]['mecanico'];
						$tipo = $res[$i]['tipo'];
						$id = $res[$i]['id'];

						$data = implode('/', array_reverse(explode('-', $data)));
						$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));

						$valorPeca = $valor - $valor_mao_obra;
						$totalValores = $totalValores + $valor;

						$valorF = number_format($valor, 2, ',', '.');
						$valor_mao_obraF = number_format($valor_mao_obra, 2, ',', '.');
						$valorPecaF = number_format($valorPeca, 2, ',', '.');
						$totalValoresF = number_format($totalValores, 2, ',', '.');


						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];

						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res_cat[0]['modelo'];
						$marca = $res_cat[0]['marca'];


						
						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];

						?>

						<tr>
							<td>
								<?php echo $descricao ?></td>
							<td>R$<?php echo $valor_mao_obraF ?></td>
							<td>R$ <?php echo $valorPecaF ?></td>
							<td>R$ <?php echo $valorF ?></td>
							<td><?php echo $data ?></td>
							<td><?php echo $garantia ?> Dias</td>
							<td><?php echo $data_entrega ?></td>
							
							
							
						</tr>
					<?php } ?>



		</table>

		<hr>


		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">	
					<span class="areaTotal"> <b> Total de Serviços : R$<?php echo $totalValoresF ?> </b> </span>
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
