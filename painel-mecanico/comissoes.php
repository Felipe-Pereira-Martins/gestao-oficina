<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "comissoes";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];

?>


<!-- DataTales Example -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Valor</th>
						<th>Serviço</th>
						<th>Tipo</th>
						<th>Data</th>
					</tr>
				</thead>

				<tbody>

					<?php 

					$query = $pdo->query("SELECT * FROM comissoes where mecanico = '$_SESSION[cpf_usuario]' order by id desc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$valor = $res[$i]['valor'];
						$tipo = $res[$i]['tipo'];
						$servico = $res[$i]['id_servico'];
						$data = $res[$i]['data'];
						
						$id = $res[$i]['id'];

						$data = implode('/', array_reverse(explode('-', $data)));
						
						$valor = number_format($valor, 2, ',', '.');
						

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
							
							
							<td>R$ <?php echo $valor ?></td>
							<td><?php echo $nome_servico ?></td>
							<td><?php echo $tipo ?></td>
							<td><?php echo $data ?></td>
							
							
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>





<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php 
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Registro";
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM os where id = '$id2' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$cliente2 = $res[0]['cliente'];
					$descricao2 = $res[0]['descricao'];
					$valor2 = $res[0]['valor'];
					$valor_mao_obra2 = $res[0]['valor_mao_obra'];
					$data2 = $res[0]['data'];
					$data_entrega2 = $res[0]['data_entrega'];
					$concluido2 = $res[0]['concluido'];
					$mecanico2 = $res[0]['mecanico'];
					$garantia2 = $res[0]['garantia'];
					$obs2 = $res[0]['obs'];


				} else {
					$titulo = "Inserir Registro";

				}


				?>

				<h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="POST">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label >CPF Cliente</label>
								<div class="row">
									<div class="col-sm-9">
										<input value="<?php echo @$cliente2 ?>" type="text" class="form-control" id="cpf" name="cliente" placeholder="CPF do Cliente">
									</div>
									<div class="col-sm-3">
										<a href="" name="btn-buscar" id="btn-buscar" class="btn btn-primary text-light"><i class="fas fa-search"></i></a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Veículo</label>
								<div id="div-veiculo">

								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Serviço (Valor Tabelado)</label>
								<select name="servico" class="form-control" id="servico">

									<?php 

									$query = $pdo->query("SELECT * FROM servicos where valor > 0 order by nome asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome_reg = $res[$i]['nome'];
										$id_reg = $res[$i]['id'];
										?>									
										<option <?php if(@$servico2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>
									<?php } ?>
									
								</select>
							</div>
						</div>

					</div>

									

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label >Data da Entrega</label>
								<input value="<?php echo @$data2 ?>" type="date" class="form-control" id="data_entrega" name="data_entrega" >
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label >Garantia (Somente Dias)</label>
								<input value="<?php echo @$garantia2 ?>" type="text" class="form-control" id="garantia" name="garantia" placeholder="Total de Dias Garantia">
							</div>

						</div>

						
					</div>


					<div class="form-group">
						<label >Observações do Veículo</label>
						<textarea type="text" class="form-control" id="obs" name="obs"><?php echo @$obs2 ?></textarea>
					</div>
					

					<small>
						<div id="mensagem">

						</div>
					</small> 

				</div>



				<div class="modal-footer">



					<input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
					<input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">
					

					<button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
				</div>
			</form>
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





<div class="modal" id="modal-concluir" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Concluir Serviço</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<p>Deseja realmente Concluir este Serviço?</p>

				<div align="center" id="mensagem_concluir" class="">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-concluir">Cancelar</button>
				<form method="post">

					<input type="hidden" id="id"  name="id" value="<?php echo @$_GET['id'] ?>" required>

					<button type="button" id="btn-concluir" name="btn-concluir" class="btn btn-success">Concluir</button>
				</form>
			</div>
		</div>
	</div>
</div>





<?php 

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
	echo "<script>$('#modal-deletar').modal('show');</script>";
}


if (@$_GET["funcao"] != null && @$_GET["funcao"] == "concluir") {
	echo "<script>$('#modal-concluir').modal('show');</script>";
}

?>




<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM OU SEM IMAGEM -->
<script type="text/javascript">
	$("#form").submit(function () {
		var pag = "<?=$pag?>";
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: pag + "/inserir.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso!") {
                    //$('#nome').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pag="+pag;
                } else {
                	$('#mensagem').addClass('text-danger')
                }
                $('#mensagem').text(mensagem)
            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {  // Custom XMLHttpRequest
            	var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                	myXhr.upload.addEventListener('progress', function () {
                		/* faz alguma coisa durante o progresso do upload */
                	}, false);
                }
                return myXhr;
            }
        });
	});
</script>





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





<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function () {
		var pag = "<?=$pag?>";
		$('#btn-concluir').click(function (event) {
			event.preventDefault();
			$.ajax({
				url: pag + "/concluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function (mensagem) {

					if (mensagem.trim() === 'Concluído com Sucesso!') {
						$('#btn-cancelar-concluir').click();
						window.location = "index.php?pag=" + pag;
					}
					$('#mensagem_concluir').text(mensagem)

				},

			})
		})
	})
</script>




<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function () {

		$('#btn-buscar').click(function (event) {
			event.preventDefault();

			var pag = "<?=$pag?>";
			var funcao = "<?=$funcao?>";

			if(funcao.trim() === 'editar'){
				var veiculo = "<?=$veiculo2?>";
				var cpf = "<?=$cliente2?>";
			}else{
				var veiculo = "";
				var cpf = document.getElementById('cpf').value;
			}
			
			$.ajax({
				url: pag + "/buscar-veiculo.php",
				method: "post",
				data: {cpf, veiculo},
				dataType: "html",
				success: function (result) {

					$('#div-veiculo').html(result);

				},

			})
		})
	})
</script>




<script type="text/javascript">
	$(document).ready(function () {


		var funcao = "<?=$funcao?>";

		if(funcao.trim() === 'editar'){
			$('#btn-buscar').click();
		}else{
			$('#div-veiculo').text('Busque pelo CPF ao Lado');
		}


		$('#dataTable').dataTable({
			"ordering": false
		})

		$('#dataTable2').dataTable({
			"ordering": false
		})

	});
</script>



