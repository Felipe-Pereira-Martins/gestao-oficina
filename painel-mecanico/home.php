<?php
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

require_once("../conexao.php"); 


//totais dos cards
$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual."-".$mes_atual."-01";

$query_cat = $pdo->query("SELECT * FROM os where mecanico = '$_SESSION[cpf_usuario]' and concluido = 'Sim' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalAprovados = @count($res_cat);

$query_cat = $pdo->query("SELECT * FROM os where mecanico = '$_SESSION[cpf_usuario]' and concluido != 'Sim' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalPendentes = @count($res_cat);


$totalComissoesHoje = 0;
$query_cat = $pdo->query("SELECT * FROM comissoes where data = curDate() and mecanico = '$_SESSION[cpf_usuario]' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res_cat); $i++) { 
	foreach ($res_cat[$i] as $key => $value) {
	}
	$valor = $res_cat[$i]['valor'];
	$totalComissoesHoje = $totalComissoesHoje + $valor;
	
}
$totalComissoesHoje = number_format($totalComissoesHoje, 2, ',', '.');


$totalComissoesMes = 0;
$query_cat = $pdo->query("SELECT * FROM comissoes where data >= '$dataInicioMes' and data <= curDate() and mecanico = '$_SESSION[cpf_usuario]' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res_cat); $i++) { 
	foreach ($res_cat[$i] as $key => $value) {
	}
	$valor = $res_cat[$i]['valor'];
	$totalComissoesMes = $totalComissoesMes + $valor;
	
}
$totalComissoesMes = number_format($totalComissoesMes, 2, ',', '.');

?>

<div class="row">
	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Serviços Concluídos</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo @$totalAprovados ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-clipboard-list fa-2x text-success"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-danger shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Serviços Pendentes</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo @$totalPendentes ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-clipboard-list fa-2x text-danger"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Comissões Hoje</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo @$totalComissoesHoje ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-dollar-sign fa-2x text-info"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Pending Requests Card Example -->
	<div class="col-xl-3 col-md-6 mb-4">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Comissões Mês</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo @$totalComissoesMes ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-dollar-sign fa-2x text-success"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="text-xs font-weight-bold text-secondary text-uppercase mt-4">SERVIÇOS PENDENTES</div>
<hr> 

<div class="row">

	<?php 

	$query_cat = $pdo->query("SELECT * FROM os where concluido != 'Sim' and mecanico = '$_SESSION[cpf_usuario]' order by data_entrega asc, id asc limit 12");
	$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
	for ($i=0; $i < @count($res_cat); $i++) { 
		foreach ($res_cat[$i] as $key => $value) {
		}
		$data_entrega = $res_cat[$i]['data_entrega'];
		$descricao = $res_cat[$i]['descricao'];
		$veiculo = $res_cat[$i]['veiculo'];

		if($data_entrega <= date('Y-m-d')){
			$classe = 'text-danger';
			$classe2 = 'border-left-danger';
		}else{
			$classe = 'text-warning';
			$classe2 = 'border-left-warning';
		}

		$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));

		$query = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res[0]['modelo'];
						$marca = $res[0]['marca'];

		?>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card <?php echo $classe2 ?> shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold  <?php echo $classe ?> text-uppercase"><?php echo $marca . ' - ' .$modelo ?></div>
							<div class="text-xs text-secondary"><?php echo $descricao ?> </div>
						</div>
						<div class="col-auto" align="center">
							<i class="far fa-calendar-alt fa-2x  <?php echo $classe ?>"></i><br>
							<span class="text-xs"><?php echo $data_entrega ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>

</div>


