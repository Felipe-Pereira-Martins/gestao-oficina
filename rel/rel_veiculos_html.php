<?php 
require_once("../conexao.php"); 
@session_start();


setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))));

?>

<!DOCTYPE html>
<html>
<head>
	<title>Veículos na Oficina</title>
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
			position:absolute;
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
				<span class="titulorel"> Relatório de Veículos  </span>
			</div>
			<div class="col-sm-4 direita" align="right">	
				<big> <small> Data: <?php echo $data_hoje; ?></small> </big>
			</div>
		</div>


		<hr>


		<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
			<tr bgcolor='#f9f9f9' >
				<th><b>Modelo</b></th>
				<th><b>Placa</b></th>
				<th><b>Cliente</b></th>
				<th><b>Mecânico</b></th>
				<th><b>Data Entrada</b></th>
				<th><b>Serviço</b></th>

			</tr>

			<?php 

			$query_c = $pdo->query("SELECT * FROM controles order by id asc ");
			$res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
			for ($i=0; $i < @count($res_c); $i++) { 
				foreach ($res_c[$i] as $key => $value) {
				}
				$veiculo = $res_c[$i]['veiculo'];
				$mecanico = $res_c[$i]['mecanico'];
				$data = $res_c[$i]['data'];
				$descricao = $res_c[$i]['descricao'];
				$id = $res_c[$i]['id'];

				$query = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);

				$marca = $res[0]['marca'];
				$modelo = $res[0]['modelo'];
				$placa = $res[0]['placa'];
				$cliente = $res[0]['cliente'];


				$data = implode('/', array_reverse(explode('-', $data)));


				$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
				$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$nome_cli = $res_cat[0]['nome'];

				$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
				$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$nome_mec = $res_cat[0]['nome'];

				?>

				<tr>
					<td><?php echo $marca .' - '.$modelo ?></td>

					<td><?php echo $placa ?></td>

					<td><?php echo $nome_cli ?></td>
					<td><?php echo $nome_mec ?></td>
					<td><?php echo $data ?></td>
					<td><?php echo $descricao ?></td>


				</tr>
			<?php } ?>


		</table>

		<hr>




	</div>


	<div class="footer">
		<p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p> 
	</div>




</body>
</html>
