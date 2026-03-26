<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "retornos";
require_once("../conexao.php"); 


$data_hoje = date('Y-m-d');
$data_retorno = date('Y-m-d', strtotime("-$dias_alerta_retorno days",strtotime($data_hoje)));


?>


<!-- DataTales Example -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Modelo</th>
						<th>Placa</th>
						<th>Cliente</th>
						<th>Telefone</th>
						<th>Último Serviço</th>
						<th>Serviço</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

					<?php 

					$query_c = $pdo->query("SELECT * FROM retornos where data_serv <= '$data_retorno' and data_contato <= '$data_retorno' order by id asc ");
					$res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
					for ($i=0; $i < @count($res_c); $i++) { 
						foreach ($res_c[$i] as $key => $value) {
						}
						$veiculo = $res_c[$i]['veiculo'];
						$data_serv = $res_c[$i]['data_serv'];
						$data_contato = $res_c[$i]['data_contato'];
						$id = $res_c[$i]['id'];

						$data_serv = implode('/', array_reverse(explode('-', $data_serv)));



						$query = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);

						$marca = $res[0]['marca'] .' - ' .$res[0]['modelo'];						
						$placa = $res[0]['placa'];
						$cliente = $res[0]['cliente'];
						

						

						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$tel_cli = $res_cat[0]['telefone'];
						$email_cli = $res_cat[0]['email'];

						
						$query_orc = $pdo->query("SELECT * FROM os where veiculo = '$veiculo' order by id desc limit 1 ");
						$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
						$descricao = $res_orc[0]['descricao'];

						?>

						<tr>
							<td><?php echo $marca?></td>
							
							<td><?php echo $placa ?></td>
							
							<td><?php echo $nome_cli ?></td>
							<td><?php echo $tel_cli ?></td>
							<td><?php echo $data_serv ?></td>
							<td><?php echo $descricao ?></td>
							

							<td>
								
								<a href="index.php?pag=<?php echo $pag ?>&funcao=atualizar&id=<?php echo $id ?>" class='text-success mr-1' title='Atualizar Retorno'><i class='fas fa-check'></i></a>


								<a href="index.php?pag=<?php echo $pag ?>&funcao=email&email=<?php echo $email_cli ?>&nome=<?php echo $nome_cli ?>" class='text-primary mr-1' title='Enviar Email'><i class='far fa-envelope'></i></a>
								
							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>




<?php 

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "atualizar") {
	$id_ret = $_GET["id"];
	$pdo->query("UPDATE retornos SET data_contato = curDate() WHERE id = '$id_ret'");
	echo "<script language='javascript'> window.location = 'index.php?pag=$pag'; </script>";
}


if (@$_GET["funcao"] != null && @$_GET["funcao"] == "email") {
	//ENVIAR O EMAIL COM A SENHA
    $destinatario = $_GET['email'];
    $assunto = utf8_decode($nome_oficina . ' - Promoção de Serviços');;
    $mensagem = utf8_decode('Olá '.$_GET['nome']. ', '. $mensagem_retorno . "\n" .$endereco_oficina. "\n" .$telefone_oficina);
    $cabecalhos = "From: ".$email_adm;
    @mail($destinatario, $assunto, $mensagem, $cabecalhos);
    
}


?>







<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function () {
		var pag = "<?=$pag?>";
		$('#btn-deletar').click(function (event) {
			event.preventDefault();
			$.ajax({
				url: pag + "/excluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function (mensagem) {

					if (mensagem.trim() === 'Excluído com Sucesso!') {
						$('#btn-cancelar-excluir').click();
						window.location = "index.php?pag=" + pag;
					}
					$('#mensagem_excluir').text(mensagem)

				},

			})
		})
	})
</script>



<!--SCRIPT PARA CARREGAR IMAGEM -->
<script type="text/javascript">

	function carregarImg() {

		var target = document.getElementById('target');
		var file = document.querySelector("input[type=file]").files[0];
		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);


		} else {
			target.src = "";
		}
	}

</script>





<script type="text/javascript">
	$(document).ready(function () {
		$('#dataTable').dataTable({
			"ordering": false
		})

	});
</script>



