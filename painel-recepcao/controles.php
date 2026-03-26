<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "controles";
require_once("../conexao.php"); 



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
						<th>Mecânico</th>
						<th>Data Entrada</th>
						<th>Serviço</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

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

							<td>
								
								<a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
								
							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>







<div class="modal" id="modal-deletar" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Excluir Registro</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<p>Deseja realmente Excluir este Registro?</p>

				<div align="center" id="mensagem_excluir" class="">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
				<form method="post">

					<input type="hidden" id="id"  name="id" value="<?php echo @$_GET['id'] ?>" required>

					<button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
				</form>
			</div>
		</div>
	</div>
</div>






<?php 

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
	echo "<script>$('#modal-deletar').modal('show');</script>";
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



